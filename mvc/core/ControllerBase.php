<?php
class ControllerBase
{

    // Hàm model dùng để tạo một đối tượng từ một model (được gọi từ controller)
    public function model($model)
    {
        // Yêu cầu file model tương ứng
        require_once "./mvc/models/" . $model . ".php";
        // Tạo một instance của model và trả về nó
        $instance = $model::getInstance();
        return $instance;
    }

    // Hàm view dùng để hiển thị một view với dữ liệu tương ứng
    public function view($view, $data = [])
    {
        // Yêu cầu file view tương ứng
        require_once "./mvc/views/" . $view . ".php";
    }

    // Hàm redirect dùng để chuyển hướng người dùng đến một URL mới
    public function redirect($controller, $method = "index", $args = array())
    {
        global $core; // Global biến để truy cập vào các biến toàn cục

        // Tạo đường dẫn đầy đủ dựa trên thông tin được truyền vào
        $location = $core->config->base_url . "/" . $controller . "/" . $method . "/" . implode("/", $args);

        // Sử dụng header để chuyển hướng trang
        header("Location: " . URL_ROOT . $location);
        exit; // Dừng script hiện tại
    }
}
?>