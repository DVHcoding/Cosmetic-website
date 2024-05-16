<?php
class ratingModel
{
    private static $instance = null;

    private function __construct()
    {
    }

    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new ratingModel();
        }

        return self::$instance;
    }

    public function getById($id)
    {
        $db = DB::getInstance();
        $sql = "SELECT p.id, c.name as productName, p.reply, p.content, p.star, c.image as productImage, p.userId FROM productrating p JOIN products c ON p.productId = c.id WHERE p.id='$id'";
        $result = mysqli_query($db->con, $sql)->fetch_assoc();
        return $result;
    }

    public function getByIdAdmin($Id)
    {
        $db = DB::getInstance();
        $sql = "SELECT * FROM products WHERE Id='$Id'";
        $result = mysqli_query($db->con, $sql);
        return $result;
    }

    public function getAllAdmin($page = 1, $total = 8)
    {
        if ($page <= 0) {
            $page = 1;
        }
        $tmp = ($page - 1) * $total;
        $db = DB::getInstance();
        $sql = "SELECT p.id, p.star, p.createdDate, p.content, p.reply, u.id as userId, u.fullName, c.name as productName FROM productrating p JOIN users u ON p.userId = u.id JOIN products c ON p.productId = c.id ORDER BY p.createdDate DESC LIMIT $tmp,$total";
        $result = mysqli_query($db->con, $sql);
        return $result;
    }

    public function reply($reply, $id)
    {
        $db = DB::getInstance();
        $sql = "UPDATE `productrating` SET reply = '" . $reply . "', repliedDate = '". date("y-m-d H:i:s")."' WHERE id = " . $id . "";
        $result = mysqli_query($db->con, $sql);
        return $result;
    }

    public function getCountPaging($row = 8)
    {
        $db = DB::getInstance();
        $sql = "SELECT COUNT(*) FROM productrating";
        $result = mysqli_query($db->con, $sql);
        if ($result) {
            $totalrow = intval((mysqli_fetch_all($result, MYSQLI_ASSOC)[0])['COUNT(*)']);
            return ceil($totalrow / $row);
        }
        return false;
    }

    public function delete($id)
    {
        $db = DB::getInstance();
        $sql = "UPDATE `productrating` SET reply = NULL, repliedDate = NULL WHERE id = " . $id . "";
        $result = mysqli_query($db->con, $sql);
        return $result;
    }
}
