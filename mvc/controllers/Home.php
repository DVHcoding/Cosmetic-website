<?php
class home extends ControllerBase
{
    public function Index()
    {
        if (isset($_SESSION['role']) && $_SESSION['role'] == "admin") {
            $this->redirect("Admin");
        }
        $product = $this->model("productModel");
        $Featuredproducts = $product->getFeaturedproducts();
        $Newproducts = $product->getNewproducts();
        $Discountproducts = $product->getDiscountproducts();
        // Fetch
        $FeaturedproductsList = $Featuredproducts->fetch_all(MYSQLI_ASSOC);
        $NewproductsList = $Newproducts->fetch_all(MYSQLI_ASSOC);
        $DiscountproductsList = $Discountproducts->fetch_all(MYSQLI_ASSOC);
        $this->view("client/index", [
            "headTitle" => "Trang chủ",
            "FeaturedproductsList" => $FeaturedproductsList,
            "NewproductsList" => $NewproductsList,
            "DiscountproductsList" => $DiscountproductsList
        ]);
    }

    public function About()
    {
        $this->view("client/about", [
            "headTitle" => "Giới thiệu",
        ]);
    }
}
