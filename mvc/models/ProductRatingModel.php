<?php
class ProductRatingModel
{
    private static $instance = null;

    private function __construct()
    {
    }

    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new ProductRatingModel();
        }

        return self::$instance;
    }

    public function getStarByProductId($productId)
    {
        $db     = DB::getInstance();
        $sql    = "SELECT * FROM `productrating` WHERE productId=$productId";
        $result = mysqli_query($db->con, $sql);
        if (mysqli_num_rows($result) > 0) {
            $s     = $result->fetch_all(MYSQLI_ASSOC);
            $total = 0;
            foreach ($result as $key => $value) {
                $total += $value['star'];
            }
            return $total / count($s);
        }
        return 0;
    }

    public function getByProductIdUserId($productId, $userId)
    {
        $db     = DB::getInstance();
        $sql    = "SELECT b.id, b.image, b.name, a.star, a.content FROM `productrating` a JOIN products b ON a.productId = b.id WHERE userId=$userId AND productId=$productId";
        $result = mysqli_query($db->con, $sql);
        return $result;
    }

    public function getByProductId($productId)
    {
        $db     = DB::getInstance();
        $sql    = "SELECT * FROM `productrating` p JOIN users u ON p.userId = u.id WHERE productId=$productId";
        $result = mysqli_query($db->con, $sql);
        if (mysqli_num_rows($result) > 0) {
            return $result->fetch_all(MYSQLI_ASSOC);
        }
        return [];
    }

    public function add($productId, $content, $star, $userId)
    {
        $db     = DB::getInstance();
        $sql    = "INSERT INTO `productrating`(`id`, `productId`, `userId`, `star`, `content`,`createdDate`) VALUES (NULL," . $productId . "," . $userId . "," . $star . ",'" . $content . "','" . date("y-m-d H:i:s") . "')";
        $result = mysqli_query($db->con, $sql);
        return $result;
    }
}
