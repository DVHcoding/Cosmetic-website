<?php

class orderManage extends ControllerBase
{
    public function index()
    {
        //# Không phải admin thì đẩy về trang chủ dành cho người dùng
        if (isset($_SESSION['role']) && $_SESSION['role'] != 'Admin') {
            $this->redirect("home");
        }

        // Tạo đối tượng từ model orderModel
        $order = $this->model("orderModel");

        // Nếu gõ nội dung vào ô tìm kiếm
        if (isset($_GET['keyword'])) {
            // Gọi hàm searchCategory trong CategoryModel.php
            $result    = $order->searchOrder($_GET['keyword']);
            $orderList = [];

            // Nếu có nội dung thì thêm vào mảng $orderList
            if ($result) {
                $orderList = $result->fetch_all(MYSQLI_ASSOC);
            }

            // Hiển thị ra view người dùng
            $this->view("admin/order", [
                "headTitle" => "Quản lý đơn đặt hàng",
                "orderList" => $orderList
            ]);
        } else {
            // Mặc định khi không tìm kiếm gì thì sẽ gọi hàm getAll()
            $result    = $order->getAll();
            $orderList = $result->fetch_all(MYSQLI_ASSOC);

            // Hiển thị tất cả order ra view
            $this->view("admin/order", [
                "headTitle" => "Quản lý đơn đặt hàng",
                "orderList" => $orderList
            ]);
        }

    }

    // Phương thức để hiển thị chi tiết đơn hàng
    public function detail($orderId)
    {
        // Kiểm tra xem người dùng có quyền truy cập hay không
        // Nếu người dùng không phải là Admin, chuyển hướng về trang chủ
        if (isset($_SESSION['role']) && $_SESSION['role'] != 'Admin') {
            $this->redirect("home");
        }

        // Lấy đối tượng model cho chi tiết đơn hàng
        $orderDetail = $this->model("orderDetailModel");
        // Gọi phương thức getByorderId từ model để lấy chi tiết đơn hàng theo ID
        $result = $orderDetail->getByorderId($orderId);
        // Lấy tất cả kết quả truy vấn dưới dạng mảng liên kế
        $orderDetailList = $result->fetch_all(MYSQLI_ASSOC);

        // Lấy đối tượng model cho đơn hàng
        $order = $this->model("orderModel");
        // Gọi phương thức getById từ model để lấy thông tin đơn hàng theo ID
        $result = $order->getById($orderId);

        // Hiển thị view "admin/orderDetail" với các tham số cần thiết
        $this->view("admin/orderDetail", [
            "headTitle"       => "Chi tiết đơn hàng: " . $orderId,
            "orderId"         => $orderId,
            "orderDetailList" => $orderDetailList,
            "order"           => $result->fetch_assoc()
        ]);
    }

    // Phương thức để xử lý khi đơn hàng đã được xử lý
    public function processed($orderId)
    {
        // Lấy đối tượng model cho đơn hàng
        $order = $this->model("orderModel");
        // Gọi phương thức processed từ model với tham số là ID đơn hàng
        $result = $order->processed($orderId);

        // Kiểm tra kết quả từ phương thức processed
        if ($result) {
            // Nếu thành công, chuyển hướng tới trang "orderManage"
            $this->redirect("orderManage");
        }
    }

    // Phương thức để xử lý khi đơn hàng đang được giao
    public function delivery($orderId)
    {
        // Lấy đối tượng model cho đơn hàng
        $order = $this->model("orderModel");
        // Gọi phương thức delivery từ model với tham số là ID đơn hàng
        $result = $order->delivery($orderId);
        // Kiểm tra kết quả từ phương thức delivery
        if ($result) {
            // Nếu thành công, chuyển hướng tới trang "orderManage"
            $this->redirect("orderManage");
        }
    }
}