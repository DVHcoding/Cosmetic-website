<?php

class voucherManage extends ControllerBase
{
    public function index()
    {
        // Kiểm tra nếu người dùng đã đăng nhập và không phải là Admin
        if (isset($_SESSION['role']) && $_SESSION['role'] != 'Admin') {
            // Chuyển hướng người dùng tới trang chủ
            $this->redirect("home");
        }

        // Khởi tạo model
        $voucher     = $this->model("voucherModel");
        $voucherList = ($voucher->getAll())->fetch_all(MYSQLI_ASSOC);

        $this->view("admin/voucher", [
            "headTitle"   => "Quản lý voucher",
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
                $this->redirect("voucherManage");
            } else {
                $this->view("admin/addNewVoucher", [
                    "headTitle" => "Quản lý voucher",
                    "cssClass"  => "error",
                    "message"   => "Lỗi, vui lòng thử lại sau!",
                    "name"      => $_POST['name']
                ]);
            }
        } else {
            $this->view("admin/addNewVoucher", [
                "headTitle" => "Thêm mới voucher",
                "cssClass"  => "none",
            ]);
        }
    }

    public function edit($id = "")
    {
        // Nếu người dùng không phải admin thì đẩy về trang chủ
        if (isset($_SESSION['role']) && $_SESSION['role'] != 'Admin') {
            $this->redirect("home");
        }

        // Khởi tạo models
        $voucher = $this->model("voucherModel");
        // Gọi hàm getByIdAdmin
        $vouchers = $voucher->getByIdAdmin($id);


        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Gọi hàm update
            $r = $voucher->update($_POST['id'], $_POST['name']);

            // Gọi hàm getByIdAdmin
            $new = $voucher->getByIdAdmin($_POST['id']);

            if ($r) {
                // Cập nhật thành công thì chuyển hướng về voucherManage
                $this->view("admin/editVoucher", [
                    "headTitle" => "sửa voucher",
                    "cssClass"  => "success",
                    "message"   => "Cập nhật thành công!",
                    "voucher"   => $new->fetch_assoc()
                ]);

            } else {
                $this->view("admin/editVoucher", [
                    "headTitle" => "sửa voucher",
                    "cssClass"  => "error",
                    "message"   => "Lỗi, vui lòng thử lại sau!",
                    "voucher"   => $new->fetch_assoc()
                ]);
            }

        } else {
            $this->view("admin/editVoucher", [
                "headTitle" => "sửa voucher",
                "cssClass"  => "none",
                "voucher"   => $vouchers->fetch_assoc()
            ]);
        }
    }

    public function delete($id = "")
    {
        // Nếu người dùng không phải admin thì đẩy về trang chủ
        if (isset($_SESSION['role']) && $_SESSION['role'] != 'Admin') {
            $this->redirect("home");
        }

        // Khởi tạo models
        $voucher = $this->model("voucherModel");
        // Gọi hàm delete
        $voucher->delete($id);

        $this->redirect("voucherManage");
    }


    public function changeStatus($id)
    {
        $voucher = $this->model("voucherModel");
        $result  = $voucher->changeStatus($id);
        if ($result) {
            $this->redirect("voucherManage");
        }
    }
}
