<?php

class productManage extends ControllerBase
{
    public function index()
    {
        // # Nếu k phải tài khoản admin thì đẩy về trang người dùng
        if (isset($_SESSION['role']) && $_SESSION['role'] != 'Admin') {
            $this->redirect("home");
        }

        // Tạo đối tượng product từ class productModel
        $product = $this->model("productModel");

        // # Nếu nhập thông tin vào thanh search
        if (isset($_GET['keyword'])) {
            // gọi hàm searchAdmin từ productModel
            $result      = $product->searchAdmin($_GET["keyword"]);
            $productList = [];

            // query trong database thành công thì bỏ data vào mảng productList
            if ($result) {
                $productList = $result->fetch_all(MYSQLI_ASSOC);
            }

            // Hiển thị data với nội dung tìm kiếm đó ra view
            $this->view("admin/product", [
                "headTitle"   => "Quản lý sản phẩm",
                "productList" => $productList
            ]);
        } else {

            // # Mặc định nếu không tìm kiếm gì thì sẽ hiển thị tất cả nội dung
            // # Mặc định trang sẽ là 1. Và hiển thị 8 sản phẩm 1 trang
            if (isset($_GET['page'])) {
                $productList = ($product->getAllAdmin($_GET['page']))->fetch_all(MYSQLI_ASSOC);
            } else {
                $productList = ($product->getAllAdmin('1'))->fetch_all(MYSQLI_ASSOC);
            }
            $countPaging = $product->getCountPaging(8);
            $this->view("admin/product", [
                "headTitle"   => "Quản lý sản phẩm",
                "productList" => $productList,
                "countPaging" => $countPaging
            ]);
        }

    }

    public function add()
    {
        // Kiểm tra nếu không phải tài khoản admin thì chuyển hướng về trang người dùng
        if (isset($_SESSION['role']) && $_SESSION['role'] != 'Admin') {
            $this->redirect("home");
        }

        // Tạo đối tượng category từ class categoryModel
        $category = $this->model("categoryModel");
        // Lấy tất cả danh mục để hiển thị trong form thêm mới sản phẩm
        $result       = $category->getAllClient();
        $categoryList = $result->fetch_all(MYSQLI_ASSOC);

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $product = $this->model("productModel");
            $result  = $product->insert($_POST);
            if ($result) {
                $this->view("admin/addNewProduct", [
                    "headTitle"    => "Quản lý sản phẩm",
                    "cssClass"     => "success",
                    "message"      => "Thêm mới thành công!",
                    "name"         => $_POST['name'],
                    "categoryList" => $categoryList
                ]);
            } else {
                $this->view("admin/addNewProduct", [
                    "headTitle" => "Quản lý sản phẩm",
                    "cssClass"  => "error",
                    "message"   => "Lỗi, vui lòng thử lại sau!",
                    "name"      => $_POST['name']
                ]);
            }
        } else {
            $this->view("admin/addNewProduct", [
                "headTitle"    => "Thêm mới sản phẩm",
                "cssClass"     => "none",
                "categoryList" => $categoryList
            ]);
        }
    }

    public function edit($id = "")
    {
        if (isset($_SESSION['role']) && $_SESSION['role'] != 'Admin') {
            $this->redirect("home");
        }

        $category     = $this->model("categoryModel");
        $result       = $category->getAllClient();
        $categoryList = $result->fetch_all(MYSQLI_ASSOC);

        $product = $this->model("productModel");

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $r   = $product->update($_POST);
            $new = $product->getByIdAdmin($_POST['id']);
            if ($r) {
                $this->view("admin/editProduct", [
                    "headTitle"    => "Xem/Cập nhật sản phẩm",
                    "cssClass"     => "success",
                    "message"      => "Cập nhật thành công!",
                    "categoryList" => $categoryList,
                    "product"      => $new->fetch_assoc()
                ]);
            } else {
                $this->view("admin/editProduct", [
                    "headTitle"    => "Xem/Cập nhật sản phẩm",
                    "cssClass"     => "error",
                    "message"      => "Lỗi, vui lòng thử lại sau!",
                    "categoryList" => $categoryList,
                    "product"      => $new->fetch_assoc()
                ]);
            }
        } else {
            $p = $product->getByIdAdmin($id);
            $this->view("admin/editProduct", [
                "headTitle"    => "Xem/Cập nhật sản phẩm",
                "cssClass"     => "none",
                "categoryList" => $categoryList,
                "product"      => $p->fetch_assoc()
            ]);
        }
    }

    // Controller xóa sản phẩm 
    public function delete($id = '')
    {
        // Nếu người dùng không phải admin thì đẩy về trang chủ
        if (isset($_SESSION['role']) && $_SESSION['role'] != 'Admin') {
            $this->redirect("home");
        }

        // Khởi tạo đối tượng product từ productModels
        $product = $this->model("productModel");
        // Gọi hàm delete
        $product->delete($id);

        $this->redirect("productManage");
    }

    public function changeStatus($id)
    {
        $product = $this->model("productModel");
        $result  = $product->changeStatus($id);
        if ($result) {
            $this->redirect("productManage");
        }
    }


}
