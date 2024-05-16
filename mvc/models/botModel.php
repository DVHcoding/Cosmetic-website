<?php
class botModel
{
    private static $instance = null;

    private function __construct()
    {
    }

    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new botModel();
        }

        return self::$instance;
    }

    public function getReplies($queries)
    {
        $db = DB::getInstance();
        $sql = "";
        if (count(explode(" ", $queries)) > 1) {
            $sql = "SELECT replies FROM bot WHERE MATCH(queries,replies) AGAINST ('$queries' IN NATURAL LANGUAGE MODE)";
        } else {
            $sql = "SELECT replies FROM bot WHERE replies LIKE '%" . $queries . "%' OR queries LIKE '%" . $queries . "%'";
        }
        $result = mysqli_query($db->con, $sql);
        if (mysqli_num_rows($result) > 0) {
            return $result->fetch_all(MYSQLI_ASSOC)[0];
        } else {
            return false;
        }
    }
}
