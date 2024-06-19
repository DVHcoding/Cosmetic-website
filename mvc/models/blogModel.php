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
            // Tạo một thể hiện mới của lớp blogModel
            self::$instance = new blogModel();
        }

        // Trả về thể hiện duy nhất của lớp blogModel
        return self::$instance;
    }

    // Phương thức tìm kiếm bài viết theo từ khóa
    public function search($keyword)
    {
        // Lấy thể hiện của lớp DB
        $db = DB::getInstance();
        // Câu truy vấn SQL tìm kiếm bài viết theo từ khóa trong tiêu đề 
        $sql    = "SELECT b.id, b.title, b.content, b.image, b.createdDate, u.fullName as author, MATCH (b.title) AGAINST ('" . $keyword . "') as score FROM blog b JOIN users u ON b.userId = u.id WHERE MATCH(b.title) AGAINST ('$keyword') > 0.2 ORDER BY score DESC";
        $result = mysqli_query($db->con, $sql);
        return $result;
    }

    // Hàm lấy thông tin blog theo ID
    public function getById($id)
    {
        // Lấy đối tượng kết nối cơ sở dữ liệu
        $db = DB::getInstance();
        // Câu lệnh SQL để lấy thông tin blog dựa vào ID
        $sql = "SELECT b.id, b.title, b.content, b.image, b.createdDate, u.fullName as author, b.views FROM blog b JOIN users u ON b.userId = u.id WHERE b.id = " . $id . "";
        // Thực thi câu lệnh SQL và lấy kết quả dưới dạng một hàng duy nhất
        $result = mysqli_query($db->con, $sql)->fetch_assoc();
        // Trả về kết quả
        return $result;
    }

    // Hàm lấy tất cả các blog theo trang
    public function getAll($page = 1, $total = 8)
    {
        // Nếu số trang nhỏ hơn hoặc bằng 0, đặt lại thành 1
        if ($page <= 0) {
            $page = 1;
        }
        // Tính toán giá trị bắt đầu cho truy vấn SQL
        $tmp = ($page - 1) * $total;
        // Lấy đối tượng kết nối cơ sở dữ liệu
        $db     = DB::getInstance();
        $sql    = "SELECT b.id, b.title, b.content, b.image, b.createdDate, u.fullName as author, b.views FROM blog b JOIN users u ON b.userId = u.id LIMIT $tmp,$total";
        $result = mysqli_query($db->con, $sql);
        return $result;
    }

    public function getPopular()
    {
        // Lấy thể hiện của kết nối cơ sở dữ liệu
        $db = DB::getInstance();
        // Câu lệnh SQL để lấy thông tin các bài viết phổ biến
        $sql = "SELECT b.id, b.title, b.content, b.image, b.createdDate, u.fullName as author, b.views FROM blog b JOIN users u ON b.userId = u.id WHERE b.views > 0 ORDER BY b.views DESC LIMIT 5";
        // Thực hiện truy vấn SQL
        $result = mysqli_query($db->con, $sql);
        // Trả về kết quả truy vấn
        return $result;
    }

    public function getCountPaging($row = 8)
    {
        // Lấy thể hiện của kết nối cơ sở dữ liệu
        $db = DB::getInstance();
        // Câu lệnh SQL để đếm số lượng hàng trong bảng productrating
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
