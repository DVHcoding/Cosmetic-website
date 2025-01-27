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
        $db = DB::getInstance();
        // Câu lệnh SQL để đếm số lượng hàng trong bảng productrating
        $sql = "SELECT COUNT(*) FROM productrating";
        // Thực thi câu lệnh SQL và lấy kết quả
        $result = mysqli_query($db->con, $sql);
        if ($result) {
            // Lấy tổng số hàng từ kết quả truy vấn và chuyển đổi thành số nguyên
            $totalrow = intval((mysqli_fetch_all($result, MYSQLI_ASSOC)[0])['COUNT(*)']);
            // Tính toán và trả về số trang dựa trên số hàng mỗi trang
            return ceil($totalrow / $row);
        }
        // Nếu truy vấn thất bại, trả về false
        return false;
    }

    public function insert($data)
    {
        $db = DB::getInstance();
        // Kiểm tra hình ảnh và di chuyển vào thư mục upload
        $file_name = $_FILES['image']['name'];
        $file_temp = $_FILES['image']['tmp_name'];

        // Tách phần mở rộng của file
        $div      = explode('.', $file_name);
        $file_ext = strtolower(end($div));
        // Tạo tên file duy nhất bằng cách mã hóa thời gian hiện tại
        $unique_image = substr(md5(time()), 0, 10) . '.' . $file_ext;
        // Đường dẫn đầy đủ của file sau khi upload
        $uploaded_image = APP_ROOT . "../../public/images/" . $unique_image;

        // Di chuyển file tạm vào thư mục đích
        move_uploaded_file($file_temp, $uploaded_image);

        // Câu lệnh SQL để chèn dữ liệu vào bảng blog
        $sql    = "INSERT INTO `blog`(`id`, `title`, `content`, `image`, `userId`, `createdDate`, `lastUpdated`,`views`) VALUES (NULL,'" . $data['title'] . "','" . $data['content'] . "','" . $unique_image . "','" . $_SESSION['user_id'] . "','" . date("y-m-d H:i:s") . "','" . date("y-m-d H:i:s") . "',0)";
        $result = mysqli_query($db->con, $sql);
        return $result;
    }

    public function update()
    {
        // Kiểm tra xem có hình ảnh nào được tải lên hay không và di chuyển hình ảnh đó vào thư mục upload
        if (!empty($_FILES['image']['name'])) {
            // Lấy tên file hình ảnh
            $file_name = $_FILES['image']['name'];
            // Lấy đường dẫn tạm thời của file hình ảnh 
            $file_temp = $_FILES['image']['tmp_name'];

            // Tách tên file thành mảng bằng dấu chấm
            $div = explode('.', $file_name);
            // Lấy phần mở rộng của file và chuyển thành chữ thường
            $file_ext = strtolower(end($div));
            // Tạo tên file duy nhất bằng cách hash thời gian hiện tại và lấy 10 ký tự đầu tiên
            $unique_image = substr(md5(time() . '1'), 0, 10) . '.' . $file_ext;
            // Đường dẫn của file ảnh sau khi upload
            $uploaded_image = APP_ROOT . "../../public/images/" . $unique_image;

            // Di chuyển file ảnh từ đường dẫn tạm thời vào thư mục upload
            move_uploaded_file($file_temp, $uploaded_image);
        }

        // Lấy thể hiện của đối tượng DB
        $db = DB::getInstance();
        // Tạo câu lệnh SQL để cập nhật tiêu đề, nội dung và thời gian cập nhật cuối cùng
        $sql = "UPDATE `blog` SET title = '" . $_POST['title'] . "', `content` = '" . $_POST['content'] . "', `lastUpdated` = '" . date("y-m-d H:i:s") . "'";
        if (!empty($_FILES['image']['name'])) {
            // Nếu có hình ảnh được tải lên, thêm thông tin hình ảnh vào câu lệnh SQL
            $sql .= ", `image` = '" . $unique_image . "'";
        }
        $sql .= " WHERE id = " . $_POST['id'] . "";
        $result = mysqli_query($db->con, $sql); // Thực thi câu lệnh SQL
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
