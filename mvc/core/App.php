<?php
class App
{
    // Khai báo các biến mặc định cho controller, action và params
    protected $controller = "home";
    protected $action = "Index";
    protected $params = [];

    // Hàm khởi tạo của lớp App
    function __construct()
    {
        // Xử lý URL để lấy ra controller, action và params
        $arr = $this->UrlProcess();

        // Nếu không có giá trị nào được trả về từ hàm UrlProcess, sử dụng mặc định là home/Index
        if (!$arr) {
            $arr[0] = "home";
            $arr[1] = "Index";
        }

        // Kiểm tra xem file controller có tồn tại không
        if (file_exists("./mvc/controllers/" . $arr[0] . ".php")) {
            // Nếu tồn tại, gán controller với giá trị từ URL và loại bỏ nó khỏi mảng $arr
            $this->controller = $arr[0];
            unset($arr[0]);
        }
        // Yêu cầu file controller tương ứng
        require_once "./mvc/controllers/" . $this->controller . ".php";
        // Tạo một instance của controller
        $this->controller = new $this->controller;

        // Xác định action
        if (isset($arr[1])) {
            if (method_exists($this->controller, $arr[1])) {
                $this->action = $arr[1];
            }
            unset($arr[1]);
        }

        // Lưu params
        $this->params = $arr ? array_values($arr) : [];
        // Thêm $_GET vào params
        array_push($this->params, $_GET);

        // Gọi action với các params
        call_user_func_array([$this->controller, $this->action], $this->params);
    }

    // Hàm xử lý URL, trả về mảng chứa các phần tử của URL
    function UrlProcess()
    {
        if (isset($_GET["url"])) {
            // Lấy URL và loại bỏ các ký tự '/' không cần thiết
            return explode("/", filter_var(trim($_GET["url"], "/")));
        }
    }
}
?>