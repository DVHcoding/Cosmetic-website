<?php
class cartModel
{
    private static $instance = null;

    private function __construct()
    {
    }

    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new cartModel();
        }

        return self::$instance;
    }

    /**
     * Retrieves the list of products in a user's cart by their user ID.
     *
     * This method performs a SQL JOIN between the `cart` and `products` tables
     * to get detailed product information such as name, price, image, and quantity
     * for each product in the cart.
     *
     * @param int|string $userId The ID of the user whose cart is being queried.
     * @return array Returns an associative array of cart items keyed by product ID.
     *               Each item includes productId, productName, image, quantity, and productPrice.
     *
     */
    public function getByUserId($userId)
    {
        $db = DB::getInstance();
        $sql = "SELECT c.productId, c.productName, c.productPrice, c.quantity, p.image FROM cart c JOIN products p ON c.productId = p.id WHERE userId='$userId'";
        $result = mysqli_query($db->con, $sql);
        if ($result) {
            $data = $result->fetch_all(MYSQLI_ASSOC);
            $cartArray = [];
            foreach ($data as $key => $value) {
                $cartArray[$value['productId']] = array(
                    "productId" => $value['productId'],
                    "productName" => $value['productName'],
                    "image" => $value['image'],
                    "quantity" => $value['quantity'],
                    "productPrice" => $value['productPrice']
                );
            }
        }

        return $cartArray;
    }

    /**
     * Checks if a specific product exists in a user's cart.
     *
     * Executes a SQL query to determine whether the given product is already present
     * in the cart for the specified user.
     *
     * @param int|string $userId The ID of the user.
     * @param int|string $productId The ID of the product to check in the cart.
     * @return bool Returns true if the product is in the user's cart, false otherwise.
     */
    public function check($userId, $productId)
    {
        $db = DB::getInstance();
        $sql = "SELECT * FROM cart WHERE userId='$userId' AND productId='$productId'";
        $result = mysqli_query($db->con, $sql);
        if (mysqli_num_rows($result) > 0) {
            return true;
        }
        return false;
    }

    /**
     * Retrieves the total quantity of all items in a user's cart.
     *
     * Executes a SQL query to calculate the sum of the quantity column
     * for all items in the cart belonging to the specified user.
     *
     * @param int|string $userId The ID of the user.
     * @return mysqli_result|false Returns the result set containing the total quantity as 'total',
     *                             or false on query failure.
     *
     */
    public function getTotalQuantitycart($userId)
    {
        $db = DB::getInstance();
        $sql = "SELECT SUM(quantity) as total FROM cart WHERE userId='$userId'";
        $result = mysqli_query($db->con, $sql);
        return $result;
    }

    public function getTotalPrice($userId)
    {
        $db = DB::getInstance();
        $sql = "SELECT SUM(quantity * productPrice) as total FROM cart WHERE userId='$userId'";
        $result = mysqli_query($db->con, $sql);
        return $result;
    }

    public function add($userId, $item)
    {
        $db = DB::getInstance();
        $sql = "INSERT INTO `cart`(`id`, `productId`, `productName`, `productPrice`, `quantity`, `userId`) VALUES (NULL,'" . $item['id'] . "','" . $item['name'] . "','" . $item['promotionPrice'] . "',1,'" . $userId . "')";
        $result = mysqli_query($db->con, $sql);
        return $result;
    }

    public function updateQuanity($userId, $item, $productId)
    {
        $db = DB::getInstance();
        $sql = "UPDATE `cart` SET `quantity`= quantity + 1 WHERE productId='" . $item[$productId]['productId'] . "' AND userId=$userId";
        $result = mysqli_query($db->con, $sql);
        return $result;
    }

    public function editQuanity($userId, $productId, $qty)
    {
        $db = DB::getInstance();
        $sql = "UPDATE `cart` SET `quantity`= '" . $qty . "' WHERE productId='" . $productId . "' AND userId=$userId";
        $result = mysqli_query($db->con, $sql);
        return $result;
    }

    public function remove($userId, $productId)
    {
        $db = DB::getInstance();
        $sql = "DELETE FROM `cart` WHERE productId=$productId AND userId=$userId";
        $result = mysqli_query($db->con, $sql);
        return $result;
    }

    public function deleteCart()
    {
        $db = DB::getInstance();
        $sqlDeleteCart = "DELETE FROM `cart` WHERE userId='" . $_SESSION['user_id'] . "'";
        mysqli_query($db->con, $sqlDeleteCart);
    }
}
