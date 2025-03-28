<?php

class Admin extends ControllerBase
{
    public function Index()
    {
        if ((isset($_SESSION['role']) && $_SESSION['role'] != 'Admin') || !isset($_SESSION['user_id'])) {
            // Nếu không phải admin hoặc không có phiên đăng nhập, chuyển hướng đến trang 'home'
            $this->redirect("home");
        }

        // Khởi tạo các model để truy vấn dữ liệu từ CSDL
        $user    = $this->model("userModel");
        $order   = $this->model("orderModel");
        $product = $this->model("productModel");

        // Lấy danh sách đơn hàng của người dùng hiện tại
        $result = $order->getAll($_SESSION['user_id']);
        // Fetch dữ liệu từ kết quả
        $orderList = $result->fetch_all(MYSQLI_ASSOC);

        // Lấy tổng doanh thu
        $totalRevenue = $order->getTotalRevenue();
        // Lấy tổng số đơn hàng đã hoàn thành
        $totalOrderCompleted = $order->getTotalOrderCompleted();
        // Lấy tổng số lượng khách hàng
        $totalClient = $user->getTotalClient();
        // Lấy doanh thu của từng ngày trong tháng
        $revenueMonth = $order->getRevenueMonth()->fetch_all(MYSQLI_ASSOC);

        // Tạo mảng chứa tổng doanh thu từng ngày trong tháng
        $totals = [];
        for ($i = 0; $i < count($revenueMonth); $i++) {
            $totals[$i] = $revenueMonth[$i]['total'];
        }

        // Tạo mảng chứa các ngày trong tháng
        $days = [];
        for ($i = 0; $i < count($revenueMonth); $i++) {
            $days[$i] = $revenueMonth[$i]['day'];
        }

        ///////////////////////////////////////////////
        // Lấy số lượng sản phẩm đã bán trong tháng
        $soldCountMonth = $product->getSoldCountMonth()->fetch_all(MYSQLI_ASSOC);

        // Tạo mảng chứa số lượng sản phẩm đã bán
        $totalsoldCount = [];
        for ($i = 0; $i < count($soldCountMonth); $i++) {
            $totalsoldCount[$i] = $soldCountMonth[$i]['total'];
        }

        // Tạo mảng chứa tên của các sản phẩm đã bán
        $names = [];
        for ($i = 0; $i < count($soldCountMonth); $i++) {
            $names[$i] = $soldCountMonth[$i]['name'];
        }

        // Chuyển hướng đến view và truyền dữ liệu vào
        $this->view("admin/index", [
            "headTitle"           => "Trang quản trị",
            "orderList"           => $orderList,
            "totalRevenue"        => $totalRevenue->fetch_assoc(),
            "totalClient"         => $totalClient->fetch_assoc(),
            "totalOrderCompleted" => $totalOrderCompleted->fetch_assoc(),
            "totals"              => $totals,
            "days"                => $days,
            "totalsoldCount"      => $totalsoldCount,
            "names"               => $names
        ]);
    }
}
