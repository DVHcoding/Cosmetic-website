<?php
class statisticManage extends ControllerBase
{
    public function index()
    {
        if (isset($_SESSION['role']) && $_SESSION['role'] != 'Admin') {
            $this->redirect("home");
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Khởi tạo model
            $statistic = $this->model("statisticModel");
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                if ($_POST['type'] == "revenue") {
                    $this->redirect("statisticManage", "revenue");
                } else if ($_POST['type'] == "stock") {
                    $this->redirect("statisticManage", "stock");
                } else if ($_POST['type'] == "products") {
                    $this->redirect("statisticManage", "products");
                }
            }
        }

        $this->view("admin/statistic", [
            "headTitle" => "Thống kê"
        ]);
    }

    public function statistic()
    {
        // Kiểm tra xem người dùng có quyền Admin không
        if (isset($_SESSION['role']) && $_SESSION['role'] != 'Admin') {
            $this->redirect("home");
        }

        // Kiểm tra xem yêu cầu có phải phương thức POST không
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Khởi tạo model
            $statistic = $this->model("statisticModel");
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                // Kiểm tra loại thống kê có phải là "revenue" (doanh thu) không
                if ($_POST['type'] == "revenue") {
                    // Xử lý code cho loại thống kê doanh thu...
                }
                // Lấy dữ liệu doanh thu từ model
                $result = $statistic->getRevenue();
                if ($result) {
                    // Chuyển dữ liệu kết quả thành mảng
                    $revenueList = $result->fetch_all(MYSQLI_ASSOC);
                }
            }
        }

        $this->view("admin/statistic", [
            "headTitle"   => "Thống kê",
            "revenueList" => $revenueList
        ]);
    }

    public function revenue()
    {
        if (isset($_GET['from']) && isset($_GET['to'])) {
            $statistic   = $this->model("statisticModel");
            $result      = $statistic->getRevenue($_GET['from'], $_GET['to']);
            $revenueList = [];
            if ($result) {
                $revenueList = $result->fetch_all(MYSQLI_ASSOC);
            }

            $this->view("admin/revenueStatistic", [
                "headTitle"   => "Thống kê",
                "from"        => $_GET['from'],
                "to"          => $_GET['to'],
                "revenueList" => $revenueList
            ]);
        }
        $this->view("admin/revenueStatistic", [
            "headTitle" => "Thống kê"
        ]);
    }

    public function stock()
    {
        $statistic = $this->model("statisticModel");
        $result    = $statistic->getStock();
        if ($result) {
            $stockList = $result->fetch_all(MYSQLI_ASSOC);
        }

        $this->view("admin/stockStatistic", [
            "headTitle" => "Thống kê",
            "stockList" => $stockList
        ]);
    }

    public function products()
    {
        $statistic = $this->model("statisticModel");
        $result    = $statistic->getProducts();
        if ($result) {
            $productList = $result->fetch_all(MYSQLI_ASSOC);
        }

        $this->view("admin/productStatistic", [
            "headTitle"   => "Thống kê",
            "productList" => $productList
        ]);
    }

    public function revenueToExcel($from, $to)
    {
        $statistic = $this->model("statisticModel");
        $result    = $statistic->getRevenue($from, $to);
        if ($result) {
            $revenueList = $result->fetch_all(MYSQLI_ASSOC);

            $columnHeader = '';
            $columnHeader = "STT" . "\t" . "Doanh thu" . "\t" . "Ngày" . "\t";
            $setData      = '';
            $count        = 1;
            foreach ($revenueList as $key => $value) {
                $rowData = $count . "\t";
                foreach ($value as $v) {
                    $v       = '"' . $v . '"' . "\t";
                    $rowData .= $v;
                }
                $setData .= trim($rowData) . "\n";
            }
            header("Content-type: application/octet-stream");
            header("Content-Disposition: attachment; filename=doanh-thu(" . $from . " den " . $to . ").xls");
            header("Pragma: no-cache");
            header("Expires: 0");

            echo ucwords($columnHeader) . "\n" . $setData . "\n";
        }
    }

    public function stockToExcel()
    {
        $statistic = $this->model("statisticModel");
        $result    = $statistic->getStock();
        if ($result) {
            $stockList = $result->fetch_all(MYSQLI_ASSOC);

            $columnHeader = '';
            $columnHeader = "STT" . "\t" . "Tên sản phẩm" . "\t" . "Tồn" . "\t";
            $setData      = '';
            $count        = 1;
            foreach ($stockList as $key => $value) {
                $rowData = $count . "\t";
                foreach ($value as $v) {
                    $v       = '"' . $v . '"' . "\t";
                    $rowData .= $v;
                }
                $setData .= trim($rowData) . "\n";
            }
            header("Content-type: application/octet-stream");
            header("Content-Disposition: attachment; filename=ton-kho.xls");
            header("Pragma: no-cache");
            header("Expires: 0");

            echo ucwords($columnHeader) . "\n" . $setData . "\n";
        }
    }

    public function productToExcel()
    {
        $statistic = $this->model("statisticModel");
        $result    = $statistic->getProducts();
        if ($result) {
            $productList = $result->fetch_all(MYSQLI_ASSOC);

            $columnHeader = '';
            $columnHeader = "STT" . "\t" . "Tên sản phẩm" . "\t" . "SL đã bán" . "\t";
            $setData      = '';
            $count        = 1;
            foreach ($productList as $key => $value) {
                $rowData = $count . "\t";
                foreach ($value as $v) {
                    $v       = '"' . $v . '"' . "\t";
                    $rowData .= $v;
                }
                $setData .= trim($rowData) . "\n";
            }
            header("Content-type: application/octet-stream");
            header("Content-Disposition: attachment; filename=ton-kho.xls");
            header("Pragma: no-cache");
            header("Expires: 0");

            echo ucwords($columnHeader) . "\n" . $setData . "\n";
        }
    }
}
