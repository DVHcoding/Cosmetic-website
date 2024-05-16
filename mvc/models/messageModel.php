<?php
class messageModel
{
    private static $instance = null;

    private function __construct()
    {
    }

    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new messageModel();
        }

        return self::$instance;
    }

    public function insert($fromUserId, $toUserId, $content)
    {
        $db = DB::getInstance();
        $sql = "INSERT INTO messages VALUES (NULL, '$fromUserId','$toUserId','$content')";
        $result = mysqli_query($db->con, $sql);
        return $result;
    }

    public function getData($fromUserId, $toUserId)
    {
        $db = DB::getInstance();
        $sql = "SELECT * FROM messages m WHERE fromUserId = " . $fromUserId . " OR toUserId = " . $toUserId . " OR fromUserId = " . $toUserId . " OR toUserId = " . $fromUserId;
        $result = mysqli_query($db->con, $sql);
        return $result;
    }

    public function getUserChating()
    {
        $db = DB::getInstance();
        $sql = "SELECT DISTINCT u.id, u.fullName FROM messages m JOIN users u ON m.fromUserId = u.Id WHERE u.roleId != 1";
        $result = mysqli_query($db->con, $sql);
        return $result;
    }

    public function getDataChating($fromUserId, $toUserId)
    {
        $db = DB::getInstance();
        $sql = "SELECT * FROM messages m WHERE fromUserId = " . $fromUserId . " OR toUserId = " . $toUserId . " OR fromUserId = " . $toUserId . " OR toUserId = " . $fromUserId;
        $result = mysqli_query($db->con, $sql);
        return $result;
    }
}
