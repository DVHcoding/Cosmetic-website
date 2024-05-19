<?php
class home extends ControllerBase
{
    public function Index()
    {
        // Kiểm tra nếu phiên đăng nhập tồn tại và vai trò là "admin"
        if (isset($_SESSION['role']) && $_SESSION['role'] == "admin") {
            // Chuyển hướng đến trang "Admin"
            $this->redirect("Admin");
        }

        // Khởi tạo đối tượng productModel
        $product = $this->model("productModel");
        // Lấy sản phẩm nổi bật
        $Featuredproducts = $product->getFeaturedproducts();
        // Lấy sản phẩm mới
        $Newproducts = $product->getNewproducts();
        // Lấy sản phẩm giảm giá
        $Discountproducts = $product->getDiscountproducts();
        // Fetch
        $FeaturedproductsList = $Featuredproducts->fetch_all(MYSQLI_ASSOC);
        // Lấy danh sách sản phẩm nổi bật
        $NewproductsList = $Newproducts->fetch_all(MYSQLI_ASSOC);
        // Lấy danh sách sản phẩm mới
        $DiscountproductsList = $Discountproducts->fetch_all(MYSQLI_ASSOC);
        // Lấy danh sách sản phẩm giảm giá
        // Hiển thị trang chủ với các thông tin sản phẩm
        $this->view("client/index", [
            "headTitle"            => "Trang chủ",
            "FeaturedproductsList" => $FeaturedproductsList,
            "NewproductsList"      => $NewproductsList,
            "DiscountproductsList" => $DiscountproductsList
        ]);
    }

    // Hiển thị trang "Giới thiệu"
    public function About()
    {
        $this->view("client/about", [
            "headTitle" => "Giới thiệu",
        ]);
    }
}
