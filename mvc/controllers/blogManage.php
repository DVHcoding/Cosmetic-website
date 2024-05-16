<?php

class blogManage extends ControllerBase
{
    public function index($page = 1)
    {
        // # Nếu không phải admin thì đẩy về trang người dùng
        if (isset($_SESSION['role']) && $_SESSION['role'] != 'Admin') {
            $this->redirect("home");
        }

        // # Tạo đối tượng từ model blogModel
        $blog = $this->model("blogModel");

        // # Nếu nhập vào ô tìm kiếm 
        if (isset($_GET['keyword'])) {
            // # gọi hàm searchBlog từ blogModel
            $result = $blog->searchBlog($_GET['keyword']);
            // Hiển thị 8 sản phẩm 1 trang
            $countPaging = $blog->getCountPaging(8);
            $blogList    = [];

            // # Nếu tìm kiếm được thì trả về data bỏ vô mảng blogList
            if ($result) {
                $blogList = $result->fetch_all(MYSQLI_ASSOC);
            }

            // Hiển thị dữ liệu ra view
            $this->view("admin/blog", [
                "headTitle"   => "Quản lý Blog",
                "blogList"    => $blogList,
                'countPaging' => $countPaging
            ]);
        } else {
            // # Nếu trên thanh url có ?page='..' thì chuyển đến trang đó
            if (isset($page['page'])) {
                $blogList = ($blog->getAll($page['page']))->fetch_all(MYSQLI_ASSOC);
            } else {
                // # Nếu không có thì mặc định là 1
                $blogList = ($blog->getAll('1'))->fetch_all(MYSQLI_ASSOC);
            }

            // Hiển thị 8 sản phẩm 1 trang
            $countPaging = $blog->getCountPaging(8);

            // Hiển thị dữ liệu ra view
            $this->view("admin/blog", [
                "headTitle"   => "Quản lý Blog",
                "blogList"    => $blogList,
                'countPaging' => $countPaging
            ]);
        }
    }

    public function add()
    {
        if (isset($_SESSION['role']) && $_SESSION['role'] != 'Admin') {
            $this->redirect("home");
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $blog   = $this->model("blogModel");
            $result = $blog->insert($_POST);
            if ($result) {
                $this->view("admin/addNewBlog", [
                    "headTitle" => "Quản lý Blog",
                    "cssClass"  => "success",
                    "message"   => "Thêm mới thành công!"
                ]);
            } else {
                $this->view("admin/addNewBlog", [
                    "headTitle" => "Quản lý Blog",
                    "cssClass"  => "error",
                    "message"   => "Lỗi, vui lòng thử lại sau!"
                ]);
            }
        } else {
            $this->view("admin/addNewBlog", [
                "headTitle" => "Thêm mới Blog",
                "cssClass"  => "none",
            ]);
        }
    }

    public function edit($id = "")
    {
        if (isset($_SESSION['role']) && $_SESSION['role'] != 'Admin') {
            $this->redirect("home");
        }
        $blog = $this->model("blogModel");

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $r = $blog->update();
            $b = $blog->getById($_POST['id']);
            if ($r) {
                $this->view("admin/editBlog", [
                    "headTitle" => "Xem/Cập nhật Blog",
                    "cssClass"  => "success",
                    "message"   => "Cập nhật thành công!",
                    "blog"      => $b
                ]);
            } else {
                $this->view("admin/editBlog", [
                    "headTitle" => "Xem/Cập nhật Blog",
                    "cssClass"  => "error",
                    "message"   => "Lỗi, vui lòng thử lại sau!",
                    "blog"      => $b
                ]);
            }
        } else {
            $b = $blog->getById($id);
            $this->view("admin/editBlog", [
                "headTitle" => "Xem/Cập nhật Blog",
                "cssClass"  => "none",
                "blog"      => $b
            ]);
        }
    }

    public function delete($id)
    {
        $blog   = $this->model("blogModel");
        $result = $blog->delete($id);
        if ($result) {
            $this->redirect("blogManage");
        }
    }
}
