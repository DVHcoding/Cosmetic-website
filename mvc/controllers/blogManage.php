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
        // Kiểm tra vai trò của người dùng, nếu không phải là admin thì chuyển hướng đến trang 'home'
        if (isset($_SESSION['role']) && $_SESSION['role'] != 'Admin') {
            $this->redirect("home");
        }

        // Nếu phương thức của request là POST
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $blog = $this->model("blogModel");
            // Thêm bài viết mới vào CSDL và lấy kết quả trả về
            $result = $blog->insert($_POST);
            // Hiển thị thông báo thành công hoặc lỗi
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
            // Hiển thị form thêm mới bài viết
            $this->view("admin/addNewBlog", [
                "headTitle" => "Thêm mới Blog",
                "cssClass"  => "none",
            ]);
        }
    }

    public function edit($id = "")
    {
        // Kiểm tra vai trò của người dùng, nếu không phải là admin thì chuyển hướng đến trang 'home'
        if (isset($_SESSION['role']) && $_SESSION['role'] != 'Admin') {
            $this->redirect("home");
        }
        $blog = $this->model("blogModel");

        // Nếu phương thức của request là POST
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Cập nhật thông tin của bài viết
            $r = $blog->update();
            // Lấy thông tin chi tiết của bài viết để hiển thị trong thông báo 
            $b = $blog->getById($_POST['id']);

            // Hiển thị thông báo thành công hoặc lỗi
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
            // Hiển thị form chỉnh sửa bài viết
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
        $blog = $this->model("blogModel");
        // Xóa bài viết khỏi CSDL
        $result = $blog->delete($id);

        // Chuyển hướng đến trang 'blogManage' sau khi xóa
        if ($result) {
            $this->redirect("blogManage");
        }
    }
}
