<?php
class orderModel
{
    private static $instance = null;

    private function __construct()
    {
    }

    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new orderModel();
        }

        return self::$instance;
    }

    public function getByuserId($userId)
    {
        $db     = DB::getInstance();
        $sql    = "SELECT * FROM orders WHERE userId='$userId' ORDER BY createdDate DESC";
        $result = mysqli_query($db->con, $sql);
        return $result;
    }

    public function getById($id)
    {
        $db     = DB::getInstance();
        $sql    = "SELECT * FROM orders WHERE id='$id'";
        $result = mysqli_query($db->con, $sql);
        return $result;
    }

    public function add($userId, $total, $percentDiscount = 0)
    {
        $db     = DB::getInstance();
        $newId  = $this->generateRandomString();
        $sql    = "INSERT INTO `orders` (`id`, `userId`, `createdDate`, `receivedDate`, `status`, `paymentMethod`, `paymentStatus`, `payDate`, `total`,`discount`) VALUES ('$newId', " . $_SESSION['user_id'] . ", '" . date("y-m-d H:i:s") . "', NULL, 'processing', 'COD',0,NULL,'$total',$percentDiscount)";
        $result = mysqli_query($db->con, $sql);

        if ($result) {
            $sqlCart    = "SELECT * FROM cart WHERE userId=$userId";
            $resultCart = (mysqli_query($db->con, $sqlCart))->fetch_all(MYSQLI_ASSOC);
            if (!$resultCart) {
                $resultCart = $_SESSION['cart'];
            }
            foreach ($resultCart as $key => $value) {
                $s      = "INSERT INTO `order_details` (`id`, `orderId`, `productId`, `qty`, `productPrice`, `productName`) VALUES (NULL,'" . $newId . "', '" . $value['productId'] . "', '" . $value['quantity'] . "', " . $value['productPrice'] . ", '" . $value['productName'] . "')";
                $result = mysqli_query($db->con, $s);
                // Update qty
                $sqlUpdateQty = "UPDATE products SET qty = qty - " . $value['quantity'] . " WHERE id = " . $value['productId'] . "";
                $r            = mysqli_query($db->con, $sqlUpdateQty);
            }
        } else {
            return false;
        }

        return $newId;
    }

    public function getAll()
    {
        $db     = DB::getInstance();
        $sql    = "SELECT o.id as orderId, o.createdDate, o.receivedDate, o.status, o.paymentStatus, o.paymentMethod, o.payDate, o.total, o.discount, u.id as userId, u.fullName FROM orders o JOIN users u ON o.userId = u.id ORDER BY o.createdDate DESC";
        $result = mysqli_query($db->con, $sql);
        return $result;
    }

    public function processed($Id)
    {
        $db     = DB::getInstance();
        $sql    = "UPDATE orders SET status = 'processed', receivedDate = '" . date('y-m-d', strtotime('+3 days')) . "' WHERE id = '$Id'";
        $result = mysqli_query($db->con, $sql);
        return $result;
    }

    public function delivery($Id)
    {
        $db     = DB::getInstance();
        $sql    = "UPDATE orders SET status = 'delivery' WHERE id = '$Id'";
        $result = mysqli_query($db->con, $sql);
        return $result;
    }

    public function delete($Id)
    {
        $db                = DB::getInstance();
        $sql_order_details = "DELETE FROM order_details WHERE orderId = '$Id'";
        $result            = mysqli_query($db->con, $sql_order_details);

        $sql    = "DELETE FROM orders WHERE id = '$Id'";
        $result = mysqli_query($db->con, $sql);
        return $result;
    }

    public function received($Id)
    {
        $db     = DB::getInstance();
        $sql    = "UPDATE orders SET status = 'received', receivedDate = '" . date("y-m-d H:i:s") . "', paymentStatus = 1, payDate = '" . date("y-m-d H:i:s") . "' WHERE id = '$Id'";
        $result = mysqli_query($db->con, $sql);
        if ($result) {
            $sqlOrderDetail    = "SELECT * FROM `order_details` WHERE orderId = '$Id'";
            $resultOrderDetail = mysqli_query($db->con, $sqlOrderDetail);
            $listOrderDetail   = $resultOrderDetail->fetch_all(MYSQLI_ASSOC);

            foreach ($listOrderDetail as $key => $value) {
                $sqlUpdateSold    = "UPDATE products SET soldCount = soldCount + " . $value['qty'] . " WHERE id = " . $value['productId'] . "";
                $resultUpdateSold = mysqli_query($db->con, $sqlUpdateSold);
            }
        }
        return true;
    }

    public function payment($orderId, $paymentMethod)
    {
        $db     = DB::getInstance();
        $sql    = "UPDATE orders SET paymentStatus = 1, paymentMethod = '" . $paymentMethod . "', payDate = '" . date("y-m-d H:i:s") . "' WHERE id = '$orderId'";
        $result = mysqli_query($db->con, $sql);
        return $result;
    }

    public function cancel($orderId)
    {
        $db     = DB::getInstance();
        $sql    = "UPDATE orders SET status = 'cancel' WHERE id = '$orderId'";
        $result = mysqli_query($db->con, $sql);
        return $result;
    }

    public function getTotalRevenue()
    {
        $db     = DB::getInstance();
        $sql    = "SELECT SUM(total) AS total FROM orders";
        $result = mysqli_query($db->con, $sql);
        return $result;
    }

    public function getTotalOrderCompleted()
    {
        $db     = DB::getInstance();
        $sql    = "SELECT COUNT(*) AS total FROM orders WHERE status = 'received'";
        $result = mysqli_query($db->con, $sql);
        return $result;
    }

    public function getRevenueMonth()
    {
        $db     = DB::getInstance();
        $sql    = "SELECT SUM(total) AS total,DAY(createdDate) as day FROM `orders` WHERE MONTH(createdDate) = MONTH(NOW()) AND paymentStatus=1 GROUP BY DAY(createdDate), MONTH(createdDate), YEAR(createdDate)";
        $result = mysqli_query($db->con, $sql);
        return $result;
    }

    function generateRandomString($length = 10)
    {
        $characters       = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString     = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return 'DH-' . $randomString;
    }

    public function searchOrder($keyword)
    {
        $db     = DB::getInstance();
        $sql    = "SELECT o.id as orderId, o.createdDate, o.receivedDate, o.status, o.paymentStatus, o.paymentMethod, o.payDate, o.total, o.discount, u.id as userId, u.fullName 
               FROM orders o 
               JOIN users u ON o.userId = u.id 
               WHERE o.id LIKE '%$keyword%' 
               ORDER BY o.createdDate DESC";
        $result = mysqli_query($db->con, $sql);
        if (mysqli_num_rows($result)) {
            return $result;
        }
        return false;
    }
}
