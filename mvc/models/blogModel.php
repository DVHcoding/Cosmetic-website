<?php
class blogModel
{
    private static $instance = null;

    private function __construct()
    {
    }

    // Phương thức tĩnh để lấy thể hiện duy nhất của lớp blogModel
    public static function getInstance()
    {
        // Kiểm tra nếu chưa có thể hiện nào của lớp blogModel
        if (!self::$instance) {
            self::$instance = new blogModel();
        }

        return self::$instance;
    }

    public function search($keyword)
    {
        $db     = DB::getInstance();
        $sql    = "SELECT b.id, b.title, b.content, b.image, b.createdDate, u.fullName as author, MATCH (b.title) AGAINST ('" . $keyword . "') as score FROM blog b JOIN users u ON b.userId = u.id WHERE MATCH(b.title) AGAINST ('$keyword') > 0.2 ORDER BY score DESC";
        $result = mysqli_query($db->con, $sql);
        return $result;
    }

    public function getById($id)
    {
        $db     = DB::getInstance();
        $sql    = "SELECT b.id, b.title, b.content, b.image, b.createdDate, u.fullName as author, b.views FROM blog b JOIN users u ON b.userId = u.id WHERE b.id = " . $id . "";
        $result = mysqli_query($db->con, $sql)->fetch_assoc();
        return $result;
    }

    public function getAll($page = 1, $total = 8)
    {
        if ($page <= 0) {
            $page = 1;
        }
        $tmp    = ($page - 1) * $total;
        $db     = DB::getInstance();
        $sql    = "SELECT b.id, b.title, b.content, b.image, b.createdDate, u.fullName as author, b.views FROM blog b JOIN users u ON b.userId = u.id LIMIT $tmp,$total";
        $result = mysqli_query($db->con, $sql);
        return $result;
    }

    public function getPopular()
    {
        $db     = DB::getInstance();
        $sql    = "SELECT b.id, b.title, b.content, b.image, b.createdDate, u.fullName as author, b.views FROM blog b JOIN users u ON b.userId = u.id WHERE b.views > 0 ORDER BY b.views DESC LIMIT 5";
        $result = mysqli_query($db->con, $sql);
        return $result;
    }

    public function getCountPaging($row = 8)
    {
        $db     = DB::getInstance();
        $sql    = "SELECT COUNT(*) FROM productrating";
        $result = mysqli_query($db->con, $sql);
        if ($result) {
            $totalrow = intval((mysqli_fetch_all($result, MYSQLI_ASSOC)[0])['COUNT(*)']);
            return ceil($totalrow / $row);
        }
        return false;
    }

    public function insert($data)
    {
        $db = DB::getInstance();
        // Check image and move to upload folder
        $file_name = $_FILES['image']['name'];
        $file_temp = $_FILES['image']['tmp_name'];

        $div            = explode('.', $file_name);
        $file_ext       = strtolower(end($div));
        $unique_image   = substr(md5(time()), 0, 10) . '.' . $file_ext;
        $uploaded_image = APP_ROOT . "../../public/images/" . $unique_image;

        move_uploaded_file($file_temp, $uploaded_image);

        $sql    = "INSERT INTO `blog`(`id`, `title`, `content`, `image`, `userId`, `createdDate`, `lastUpdated`,`views`) VALUES (NULL,'" . $data['title'] . "','" . $data['content'] . "','" . $unique_image . "','" . $_SESSION['user_id'] . "','" . date("y-m-d H:i:s") . "','" . date("y-m-d H:i:s") . "',0)";
        $result = mysqli_query($db->con, $sql);
        return $result;
    }

    public function update()
    {
        // Check image and move to upload folder
        if (!empty($_FILES['image']['name'])) {
            $file_name = $_FILES['image']['name'];
            $file_temp = $_FILES['image']['tmp_name'];

            $div            = explode('.', $file_name);
            $file_ext       = strtolower(end($div));
            $unique_image   = substr(md5(time() . '1'), 0, 10) . '.' . $file_ext;
            $uploaded_image = APP_ROOT . "../../public/images/" . $unique_image;

            move_uploaded_file($file_temp, $uploaded_image);
        }

        $db  = DB::getInstance();
        $sql = "UPDATE `blog` SET title = '" . $_POST['title'] . "', `content` = '" . $_POST['content'] . "', `lastUpdated` = '" . date("y-m-d H:i:s") . "'";
        if (!empty($_FILES['image']['name'])) {
            $sql .= ", `image` = '" . $unique_image . "'";
        }
        $sql .= " WHERE id = " . $_POST['id'] . "";
        $result = mysqli_query($db->con, $sql);
        return $result;
    }

    public function view($id)
    {
        $db     = DB::getInstance();
        $sql    = "UPDATE `blog` SET views = views + 1 WHERE id = " . $id . "";
        $result = mysqli_query($db->con, $sql);
        return $result;
    }

    public function delete($id)
    {
        $db     = DB::getInstance();
        $sql    = "DELETE FROM `blog` WHERE id = " . $id . "";
        $result = mysqli_query($db->con, $sql);
        return $result;
    }

    // Hàm tìm kiếm tên blog
    public function searchBlog($keyword)
    {
        // khởi tạo database
        $db     = DB::getInstance();
        $sql    = "SELECT b.id, b.title, b.content, b.image, b.createdDate, u.fullName as author, b.views FROM blog b JOIN users u ON b.userId = u.id WHERE b.title LIKE '%$keyword%'";
        $result = mysqli_query($db->con, $sql);
        if (mysqli_num_rows($result)) {
            return $result;
        }
        return false;
    }
}
