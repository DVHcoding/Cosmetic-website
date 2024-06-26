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

        // Kiểm tra nếu request method là POST thì tiến hành thêm sản phẩm mới
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Tạo đối tượng product từ class productModel
            $product = $this->model("productModel");
            // Gọi hàm insert để thêm sản phẩm mới với dữ liệu từ form
            $result = $product->insert($_POST);
            // Nếu thêm mới thành công thì hiển thị thông báo thành công 
            if ($result) {
                $this->view("admin/addNewProduct", [
                    "headTitle"    => "Quản lý sản phẩm",
                    "cssClass"     => "success",
                    "message"      => "Thêm mới thành công!",
                    "name"         => $_POST['name'],
                    "categoryList" => $categoryList
                ]);
            } else {
                // Nếu thêm mới thất bại thì hiển thị thông báo lỗi
                $this->view("admin/addNewProduct", [
                    "headTitle" => "Quản lý sản phẩm",
                    "cssClass"  => "error",
                    "message"   => "Lỗi, vui lòng thử lại sau!",
                    "name"      => $_POST['name']
                ]);
            }
        } else {
            // Nếu không phải request method là POST thì hiển thị form thêm mới sản phẩm
            $this->view("admin/addNewProduct", [
                "headTitle"    => "Thêm mới sản phẩm",
                "cssClass"     => "none",
                "categoryList" => $categoryList
            ]);
        }
    }

    public function edit($id = "")
    {
        // Kiểm tra nếu người dùng đã đăng nhập và không phải là Admin thì chuyển hướng đến trang chủ
        if (isset($_SESSION['role']) && $_SESSION['role'] != 'Admin') {
            $this->redirect("home");
        }

        // Tạo đối tượng từ model 'categoryModel' để làm việc với danh mục
        $category = $this->model("categoryModel");
        // Lấy tất cả các danh mục khách hàng
        $result = $category->getAllClient();
        // Lấy kết quả dưới dạng mảng liên kết
        $categoryList = $result->fetch_all(MYSQLI_ASSOC);

        // Tạo đối tượng từ model 'productModel' để làm việc với sản phẩm
        $product = $this->model("productModel");

        // Kiểm tra nếu phương thức yêu cầu là POST (tức là khi form đã được submit)
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
