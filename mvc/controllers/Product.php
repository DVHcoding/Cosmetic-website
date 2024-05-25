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


    public function single($Id)
    {
        $question = $this->model("questionModel");
        $product  = $this->model("productModel");
        $blog     = $this->model("blogModel");
        $result   = $product->getById($Id);
        // Fetch
        $p    = $result->fetch_assoc();
        $list = $product->getProductSuggest($p['name'], $p['id']);

        $index = 0;
        $s     = false;

        // Rating
        $productRating        = $this->model("productRatingModel");
        $productRatingResult  = $productRating->getStarByProductId($Id);
        $productRatingContent = $productRating->getByProductId($Id);

        // Question
        $questionContent = $question->getByProductId($Id);

        //Blog
        $blogList = $blog->search($p['name'])->fetch_all(MYSQLI_ASSOC);

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


    public function category($CateId, $page)
    {
        $product = $this->model('productModel');
        $result  = $product->getByCateId(isset($page['page']) ? $page['page'] : 1, 8, $CateId);

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


    public function rating($id)
    {
        $product       = $this->model("productModel");
        $productRating = $this->model("productRatingModel");
        $check         = $productRating->getByProductIdUserId($id, $_SESSION['user_id']);

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
