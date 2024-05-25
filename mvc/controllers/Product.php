<?php

class product extends ControllerBase
{
    public function Index()
    {

    }

    public function search()
    {
        // tạo đối tượng product từ productModel  
        $product = $this->model("productModel");
        $result  = $product->search($_GET["keyword"]);
        // $result = $product->search(urlencode($_GET["keyword"]));
        $this->view("client/products", [
            "headTitle"   => "Tìm kiếm",
            "title"       => "Tìm kiếm với từ khóa: " . $_GET['keyword'],
            "productList" => $result->fetch_all(MYSQLI_ASSOC)
        ]);
    }

    function bubble_sort($arr)
    {
        $size = count($arr) - 1;
        for ($i = 0; $i < $size; $i++) {
            for ($j = 0; $j < $size - $i; $j++) {
                $k = $j + 1;
                if ($arr[$k]['date'] > $arr[$j]['date']) {
                    list($arr[$j], $arr[$k]) = array($arr[$k], $arr[$j]);
                }
            }
        }
        return $arr;
    }


    // Phương thức lấy thông tin chi tiết của sản phẩm
    public function single($Id)
    {
        // Tạo các đối tượng model cần thiết
        $question = $this->model("questionModel");
        $product  = $this->model("productModel");
        $blog     = $this->model("blogModel");
        $result   = $product->getById($Id);
        // Fetch
        // Lấy thông tin sản phẩm theo ID
        $p = $result->fetch_assoc();
        // Lấy danh sách sản phẩm gợi ý
        $list = $product->getProductSuggest($p['name'], $p['id']);

        // Khởi tạo các biến
        $index = 0;
        $s     = false;

        // Rating
        // Lấy đánh giá sản phẩm
        $productRating        = $this->model("productRatingModel");
        $productRatingResult  = $productRating->getStarByProductId($Id);
        $productRatingContent = $productRating->getByProductId($Id);

        // Question
        // Lấy câu hỏi liên quan đến sản phẩm
        $questionContent = $question->getByProductId($Id);

        //Blog
        // Lấy danh sách blog liên quan đến sản phẩm 
        $blogList = $blog->search($p['name'])->fetch_all(MYSQLI_ASSOC);

        // Hiển thị trang chi tiết sản phẩm
        $this->view("client/single", [
            "headTitle"            => $p['name'],
            "product"              => $p,
            "productSuggest"       => $list->fetch_all(MYSQLI_ASSOC),
            "star"                 => $productRatingResult,
            "productRatingContent" => $productRatingContent,
            "questionContent"      => $questionContent,
            "blogList"             => $blogList
        ]);
    }


    // Phương thức hiển thị danh sách sản phẩm theo danh mục
    public function category($CateId, $page)
    {
        $product = $this->model('productModel');
        // Lấy danh sách sản phẩm theo danh mục và phân trang
        $result = $product->getByCateId(isset($page['page']) ? $page['page'] : 1, 8, $CateId);

        $category    = $this->model('categoryModel');
        $cate        = ($category->getById($CateId))->fetch_assoc();
        $countPaging = $product->getCountPagingByClient($CateId, 8);

        // Fetch
        $productList = $result->fetch_all(MYSQLI_ASSOC);
        $this->view('client/category', [
            "headTitle"   => "Danh mục " . $cate['name'],
            "title"       => "Danh mục " . $cate['name'],
            "productList" => $productList,
            'countPaging' => $countPaging,
            'CateId'      => $CateId
        ]);
    }


    // Phương thức đánh giá sản phẩm
    public function rating($id)
    {
        $product       = $this->model("productModel");
        $productRating = $this->model("productRatingModel");
        // Kiểm tra xem người dùng đã đánh giá sản phẩm này chưa
        $check = $productRating->getByProductIdUserId($id, $_SESSION['user_id']);

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $result = $productRating->add($_POST['productId'], $_POST['content'], $_POST['star'], $_SESSION['user_id']);
            if ($result) {
                $this->redirect("product", "single", ["Id" => $_POST['productId']]);
            } else {
                $result = $product->getById($_POST['productId'])->fetch_assoc();
                $this->view("client/rating", [
                    "headTitle" => "Đánh giá",
                    "message"   => "Lỗi khi thực hiện đánh giá, vui lòng thử lại sau!",
                    "product"   => $result
                ]);
            }
        } else {

            $status = false;
            if (mysqli_num_rows($check) > 0) {
                $p      = $productRating->getByProductIdUserId($id, $_SESSION['user_id']);
                $status = true;
            }
            $result = $product->getById($id)->fetch_assoc();
            $this->view("client/rating", [
                "headTitle"     => "Đánh giá",
                "product"       => $result,
                "status"        => $status,
                "productRating" => (isset($p) > 0 ? $p : [])
            ]);
        }
    }

    public function addQuestion()
    {
        $question = $this->model("questionModel");
        $result   = $question->add($_POST['productId'], $_POST['content'], $_SESSION['user_id']);
        if ($result) {
            echo '<script>window.history.back();</script>';
        } else {
            echo '<script>alert("Lỗi!");window.history.back();</script>';
        }
    }
}
