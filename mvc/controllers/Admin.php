<?php

class Admin extends ControllerBase
{
    public function Index()
    {
        if ((isset($_SESSION['role']) && $_SESSION['role'] != 'Admin') || !isset($_SESSION['user_id'])) {
            $this->redirect("home");
        }

        $user = $this->model("userModel");
        $order = $this->model("orderModel");
        $product = $this->model("productModel");
        $result = $order->getAll($_SESSION['user_id']);
        // Fetch
        $orderList = $result->fetch_all(MYSQLI_ASSOC);

        $totalRevenue = $order->getTotalRevenue();
        $totalOrderCompleted = $order->getTotalOrderCompleted();
        $totalClient = $user->getTotalClient();
        $revenueMonth = $order->getRevenueMonth()->fetch_all(MYSQLI_ASSOC);
        $totals = [];
        for ($i = 0; $i < count($revenueMonth); $i++) {
            $totals[$i] = $revenueMonth[$i]['total'];
        }

        $days = [];
        for ($i = 0; $i < count($revenueMonth); $i++) {
            $days[$i] = $revenueMonth[$i]['day'];
        }

        ///////////////////////////////////////////////
        $soldCountMonth = $product->getSoldCountMonth()->fetch_all(MYSQLI_ASSOC);

        $totalsoldCount = [];
        for ($i = 0; $i < count($soldCountMonth); $i++) {
            $totalsoldCount[$i] = $soldCountMonth[$i]['total'];
        }

        $names = [];
        for ($i = 0; $i < count($soldCountMonth); $i++) {
            $names[$i] = $soldCountMonth[$i]['name'];
        }

        $this->view("admin/index", [
            "headTitle" => "Trang quản trị",
            "orderList" => $orderList,
            "totalRevenue" => $totalRevenue->fetch_assoc(),
            "totalClient" => $totalClient->fetch_assoc(),
            "totalOrderCompleted" => $totalOrderCompleted->fetch_assoc(),
            "totals" => $totals,
            "days" => $days,
            "totalsoldCount" => $totalsoldCount,
            "names" => $names
        ]);
    }
}
