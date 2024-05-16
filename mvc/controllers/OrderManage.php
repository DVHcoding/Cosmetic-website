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

    public function detail($orderId)
    {
        if (isset($_SESSION['role']) && $_SESSION['role'] != 'Admin') {
            $this->redirect("home");
        }

        $orderDetail = $this->model("orderDetailModel");
        $result      = $orderDetail->getByorderId($orderId);
        // Fetch
        $orderDetailList = $result->fetch_all(MYSQLI_ASSOC);

        $order  = $this->model("orderModel");
        $result = $order->getById($orderId);

        $this->view("admin/orderDetail", [
            "headTitle"       => "Chi tiết đơn hàng: " . $orderId,
            "orderId"         => $orderId,
            "orderDetailList" => $orderDetailList,
            "order"           => $result->fetch_assoc()
        ]);
    }

    public function processed($orderId)
    {
        $order  = $this->model("orderModel");
        $result = $order->processed($orderId);
        if ($result) {
            $this->redirect("orderManage");
        }
    }

    public function delivery($orderId)
    {
        $order  = $this->model("orderModel");
        $result = $order->delivery($orderId);
        if ($result) {
            $this->redirect("orderManage");
        }
    }
}