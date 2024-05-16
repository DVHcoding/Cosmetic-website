<?php

class categoryManage extends ControllerBase
{
    public function index($page = 1)
    {
        // # Kiểm tra tài khoản nếu không phải admin thì đẩy về trang chủ
        if (isset($_SESSION['role']) && $_SESSION['role'] != 'Admin') {
            $this->redirect("home");
        }
        // Khởi tạo đối tượng category từ categoryModel
        $category = $this->model("categoryModel");


        // Trường hợp nếu admin nhập thông tin vào ô tìm kiếm 
        if (isset($_GET['keyword'])) {
            // # gọi hàm searchCategory từ categoryModel và chuyền cho nó 1 argument là từ cần tìm
            $result       = $category->searchCategory($_GET["keyword"]);
            $categoryList = [];

            // # Chỉ hiển thị 8 cái cho mỗi page
            $countPaging = $category->getCountPaging(8);
            // # Nếu tìm thành công thì cho đống data vào mảng $categoryList
            if ($result) {
                $categoryList = $result->fetch_all(MYSQLI_ASSOC);
            }

            // # Dùng data đó đẩy ra view
            $this->view("admin/category", [
                "headTitle"    => "Quản lý danh mục",
                "categoryList" => $categoryList,
                'countPaging'  => $countPaging
            ]);
        } else {
            // Trường hợp không tìm gì thì hiện ra tất cả. Tuy nhiên vẫn chỉ hiện 8 cái mỗi page
            // Nếu trên thanh url có ?page=... thì lấy cái trang đó
            if (isset($page['page'])) {
                $categoryList = ($category->getAllAdmin($page['page']))->fetch_all(MYSQLI_ASSOC);
            } else {
                // Nếu k có thì mặc định là page đầu tiên
                $categoryList = ($category->getAllAdmin('1'))->fetch_all(MYSQLI_ASSOC);
            }

            $countPaging = $category->getCountPaging(8);

            $this->view("admin/category", [
                "headTitle"    => "Quản lý danh mục",
                "categoryList" => $categoryList,
                'countPaging'  => $countPaging
            ]);
        }
    }

    public function add()
    {
        if (isset($_SESSION['role']) && $_SESSION['role'] != 'Admin') {
            $this->redirect("home");
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Khởi tạo model
            $category = $this->model("categoryModel");
            // Gọi hàm insert để thêm mới vào csdl
            $result = $category->insert($_POST['name']);
            if ($result) {
                $this->view("admin/addNewCategory", [
                    "headTitle" => "Quản lý danh mục",
                    "cssClass"  => "success",
                    "message"   => "Thêm mới thành công!",
                    "name"      => $_POST['name']
                ]);
            } else {
                $this->view("admin/addNewCategory", [
                    "headTitle" => "Quản lý danh mục",
                    "cssClass"  => "error",
                    "message"   => "Lỗi, vui lòng thử lại sau!",
                    "name"      => $_POST['name']
                ]);
            }
        } else {
            $this->view("admin/addNewCategory", [
                "headTitle" => "Thêm mới danh mục",
                "cssClass"  => "none",
            ]);
        }
    }

    public function edit($id = "")
    {
        if (isset($_SESSION['role']) && $_SESSION['role'] != 'Admin') {
            $this->redirect("home");
        }

        // Khởi tạo models
        $category = $this->model("categoryModel");
        // Gọi hàm getByIdAdmin
        $c = $category->getByIdAdmin($id);


        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Gọi hàm update
            $r = $category->update($_POST['id'], $_POST['name']);

            // Gọi hàm getByIdAdmin
            $new = $category->getByIdAdmin($_POST['id']);

            if ($r) {
                // Cập nhật thành công thì chuyển hướng về categoryManage
                $this->view("admin/editCategory", [
                    "headTitle" => "Xem/Sửa danh mục",
                    "cssClass"  => "success",
                    "message"   => "Cập nhật thành công!",
                    "category"  => $new->fetch_assoc()
                ]);

            } else {
                $this->view("admin/editCategory", [
                    "headTitle" => "Xem/Cập nhật danh mục",
                    "cssClass"  => "error",
                    "message"   => "Lỗi, vui lòng thử lại sau!",
                    "category"  => $new->fetch_assoc()
                ]);
            }

        } else {
            $this->view("admin/editCategory", [
                "headTitle" => "Xem/Cập nhật danh mục",
                "cssClass"  => "none",
                "category"  => $c->fetch_assoc()
            ]);
        }
    }

    public function changeStatus($id)
    {
        $category = $this->model("categoryModel");
        $result   = $category->changeStatus($id);
        if ($result) {
            $this->redirect("categoryManage");
        }
    }
}
