<?php
class questionModel
{
    private static $instance = null;

    private function __construct()
    {
    }

    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new questionModel();
        }

        return self::$instance;
    }

    public function getById($id)
    {
        $db = DB::getInstance();
        $sql = "SELECT p.id, p.reply, p.content, p.userId FROM question p WHERE p.id='$id'";
        $result = mysqli_query($db->con, $sql)->fetch_assoc();
        return $result;
    }

    public function getAllAdmin($page = 1, $total = 8)
    {
        if ($page <= 0) {
            $page = 1;
        }
        $tmp = ($page - 1) * $total;
        $db = DB::getInstance();
        $sql = "SELECT p.id, p.content, p.reply, p.createdDate, u.id as userId, u.fullName, c.name as productName, c.name as productName FROM question p JOIN users u ON p.userId = u.id JOIN products c ON p.productId = c.id ORDER BY p.createdDate DESC LIMIT $tmp,$total";
        $result = mysqli_query($db->con, $sql);
        return $result;
    }

    public function getCountPaging($row = 8)
    {
        $db = DB::getInstance();
        $sql = "SELECT COUNT(*) FROM question";
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
        $sql = "UPDATE `question` SET reply = NULL, repliedDate = NULL WHERE id = " . $id . "";
        $result = mysqli_query($db->con, $sql);
        return $result;
    }

    public function getByProductId($productId)
    {
        $db = DB::getInstance();
        $sql = "SELECT * FROM `question` p JOIN users u ON p.userId = u.id WHERE productId=$productId ORDER BY p.id DESC";
        $result = mysqli_query($db->con, $sql);
        if (mysqli_num_rows($result) > 0) {
            return $result->fetch_all(MYSQLI_ASSOC);
        }
        return [];
    }

    public function add($productId, $content, $userId)
    {
        $db = DB::getInstance();
        $sql = "INSERT INTO `question`(`id`, `productId`, `userId`, `content`,`createdDate`) VALUES (NULL," . $productId . "," . $userId . ",'" . $content . "','" . date("y-m-d H:i:s") . "')";
        $result = mysqli_query($db->con, $sql);
        return $result;
    }

    public function reply($reply, $id)
    {
        $db = DB::getInstance();
        $sql = "UPDATE `question` SET reply = '" . $reply . "', repliedDate = '". date("y-m-d H:i:s")."' WHERE id = " . $id . "";
        $result = mysqli_query($db->con, $sql);
        return $result;
    }
}
