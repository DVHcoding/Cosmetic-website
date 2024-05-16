<?php
class orderDetailModel
{
    private static $instance = null;

    private function __construct()
    {
    }

    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new orderDetailModel();
        }

        return self::$instance;
    }

    public function getByorderId($orderId)
    {
        $db = DB::getInstance();
        $sql = "SELECT o.id, o.productId, o.qty, o.productPrice, o.productName, p.image as productImage FROM order_details o JOIN products p ON o.productId = p.id WHERE o.orderId='$orderId'";
        $result = mysqli_query($db->con, $sql);
        return $result;
    }
}
