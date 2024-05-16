<?php

class voucherManage extends ControllerBase
{
    public function index()
    {
        if (isset($_SESSION['role']) && $_SESSION['role'] != 'Admin') {
            $this->redirect("home");
        }

        // Khởi tạo model
        $voucher = $this->model("voucherModel");
        $voucherList = ($voucher->getAll())->fetch_all(MYSQLI_ASSOC);

        $this->view("admin/voucher", [
            "headTitle" => "Quản lý voucher",
            "voucherList" => $voucherList
        ]);
    }

    public function add()
    {
        if (isset($_SESSION['role']) && $_SESSION['role'] != 'Admin') {
            $this->redirect("home");
        }
        
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Khởi tạo model
            $voucher = $this->model("voucherModel");
            // Gọi hàm insert để thêm mới vào csdl
            $result = $voucher->insert($_POST);
            if ($result) {
                $this->view("admin/addNewVoucher", [
                    "headTitle" => "Quản lý voucher",
                    "cssClass" => "success",
                    "message" => "Thêm mới thành công!",
                    "name" => $_POST['name']
                ]);
            } else {
                $this->view("admin/addNewVoucher", [
                    "headTitle" => "Quản lý voucher",
                    "cssClass" => "error",
                    "message" => "Lỗi, vui lòng thử lại sau!",
                    "name" => $_POST['name']
                ]);
            }
        } else {
            $this->view("admin/addNewVoucher", [
                "headTitle" => "Thêm mới voucher",
                "cssClass" => "none",
            ]);
        }
    }

    public function changeStatus($id)
    {
        $voucher = $this->model("voucherModel");
        $result = $voucher->changeStatus($id);
        if ($result) {
            $this->redirect("voucherManage");
        }
    }
}
