<?php
class statisticModel
{
    private static $instance = null;

    private function __construct()
    {
    }

    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new statisticModel();
        }

        return self::$instance;
    }

    public function getRevenue($from,$to)
    {
        $db = DB::getInstance();
        $sql = "SELECT SUM(total) AS total,createdDate as day FROM `orders` WHERE createdDate >= '".$from."' AND createdDate <= '".$to."' AND paymentStatus=1 GROUP BY MONTH(createdDate), YEAR(createdDate)";
        $result = mysqli_query($db->con, $sql);
        if (mysqli_num_rows($result) > 0) {
            return $result;
        }
        return false;
    }

    public function getStock()
    {
        $db = DB::getInstance();
        $sql = "SELECT name, qty FROM `products`";
        $result = mysqli_query($db->con, $sql);
        if (mysqli_num_rows($result) > 0) {
            return $result;
        }
        return false;
    }

    public function getProducts()
    {
        $db = DB::getInstance();
        $sql = "SELECT name, soldCount FROM products WHERE soldCount > 0 ORDER BY soldCount DESC";
        $result = mysqli_query($db->con, $sql);
        if (mysqli_num_rows($result) > 0) {
            return $result;
        }
        return false;
    }
}
