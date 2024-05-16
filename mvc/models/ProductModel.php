<?php
class productModel
{
    private static $instance = null;

    private function __construct()
    {
    }

    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new productModel();
        }

        return self::$instance;
    }

    // public function search($keyword)
    // {
    //     $contents = file_get_contents("http://localhost:8983/solr/products/select?q=name:%20(" . $keyword . ")%0Ades:%20(" . $keyword . ")&wt=php&rows=999");
    //     $result = 0;
    //     eval("\$result = " . $contents . ";");
    //     return $result['response']['docs'];
    // }

    public function search($keyword)
    {
        $db = DB::getInstance();
        $sql = "SELECT p.id, p.name, p.image, p.originalPrice, p.promotionPrice, p.qty as qty, p.soldCount as soldCount FROM products p JOIN categories c ON p.cateId = c.id WHERE MATCH(p.name, p.des) AGAINST ('$keyword') AND p.status=1";
        $result = mysqli_query($db->con, $sql);
        return $result;
    }

    public function getProductSuggest($keyword, $id)
    {
        $db = DB::getInstance();
        $sql = "SELECT p.id, p.name, p.image, p.originalPrice, p.promotionPrice, p.qty as qty, p.soldCount as soldCount FROM products p JOIN categories c ON p.cateId = c.id WHERE MATCH(p.name, p.des) AGAINST ('$keyword') AND p.status=1 AND p.id != $id LIMIT 4";
        $result = mysqli_query($db->con, $sql);
        return $result;
    }

    // public function getProductSuggest($keyword, $id)
    // {
    //     $contents = file_get_contents("http://localhost:8983/solr/products/select?q=-id:%20" . $id . "%0Aname:%20(" . $keyword . ")&wt=php&rows=4");
    //     $result = 0;
    //     eval("\$result = " . $contents . ";");
    //     return $result['response']['docs'];
    // }

    public function getById($Id)
    {
        $db = DB::getInstance();
        $sql = "SELECT * FROM products WHERE Id='$Id' AND status=1";
        $result = mysqli_query($db->con, $sql);
        return $result;
    }

    public function getByIdAdmin($Id)
    {
        $db = DB::getInstance();
        $sql = "SELECT * FROM products WHERE Id='$Id'";
        $result = mysqli_query($db->con, $sql);
        return $result;
    }

    public function getByCateId($page = 1, $total = 8, $CateId)
    {
        if ($page <= 0) {
            $page = 1;
        }
        $tmp = ($page - 1) * $total;
        $db = DB::getInstance();
        $sql = "SELECT * FROM products WHERE cateId='$CateId' AND status=1 LIMIT $tmp,$total";
        $result = mysqli_query($db->con, $sql);
        return $result;
    }

    public function getByCateIdSinglePage($CateId, $Id)
    {
        $db = DB::getInstance();
        $sql = "SELECT * FROM products WHERE cateId='$CateId' AND status=1 AND id != $Id ORDER BY soldCount DESC LIMIT 4";
        $result = mysqli_query($db->con, $sql);
        return $result;
    }

    public function getFeaturedproducts()
    {
        $db = DB::getInstance();
        $sql = "SELECT p.id, p.name, p.image, p.originalPrice, p.promotionPrice, p.qty as qty, p.soldCount as soldCount FROM products p JOIN categories c ON p.cateId = c.id WHERE p.status=1 AND c.status = 1 AND soldCount > 0 order BY soldCount DESC LIMIT 4";
        $result = mysqli_query($db->con, $sql);
        return $result;
    }

    public function getNewproducts()
    {
        $db = DB::getInstance();
        $sql = "SELECT p.id, p.name, p.image, p.originalPrice, p.promotionPrice, p.qty as qty, p.soldCount as soldCount FROM products p JOIN categories c ON p.cateId = c.id WHERE p.status=1 AND c.status = 1 order BY id DESC LIMIT 4";
        $result = mysqli_query($db->con, $sql);
        return $result;
    }

    public function getDiscountproducts()
    {
        $db = DB::getInstance();
        $sql = "SELECT p.id, p.name, p.image, p.originalPrice, p.promotionPrice, p.qty as qty, p.soldCount as soldCount FROM products p JOIN categories c ON p.cateId = c.id WHERE p.status=1 AND c.status = 1 AND p.promotionPrice < p.originalPrice LIMIT 4";
        $result = mysqli_query($db->con, $sql);
        return $result;
    }

    public function getAllAdmin($page = 1, $total = 8)
    {
        if ($page <= 0) {
            $page = 1;
        }
        $tmp = ($page - 1) * $total;
        $db = DB::getInstance();
        $sql = "SELECT * FROM products ORDER BY createdDate DESC LIMIT $tmp,$total";
        $result = mysqli_query($db->con, $sql);
        return $result;
    }

    public function searchAdmin($keyword)
    {
        $db = DB::getInstance();
        $sql = "SELECT * FROM products WHERE name LIKE '%$keyword%'";
        $result = mysqli_query($db->con, $sql);
        if (mysqli_num_rows($result)) {
            return $result;
        }
        return false;
    }

    public function checkQuantity($Id, $qty)
    {
        $db = DB::getInstance();
        $sql = "SELECT qty FROM products WHERE status=1 AND Id='$Id'";
        $result = mysqli_query($db->con, $sql);
        $product = $result->fetch_assoc();
        if (intval($qty) > intval($product['qty'])) {
            return false;
        }
        return true;
    }

    public function updateQuantity($Id, $qty)
    {
        $db = DB::getInstance();
        $sql = "UPDATE products SET qty = qty - $qty WHERE id = $Id";
        $result = mysqli_query($db->con, $sql);
        return $result;
    }

    public function changeStatus($Id)
    {
        $db = DB::getInstance();
        $sql = "UPDATE products SET status = !status WHERE Id='$Id'";
        $result = mysqli_query($db->con, $sql);
        // file_get_contents("http://localhost:8983/solr/products/dataimport?command=full-import");
        return $result;
    }

    public function insert($product)
    {
        $db = DB::getInstance();
        // Check image and move to upload folder
        $file_name = $_FILES['image']['name'];
        $file_temp = $_FILES['image']['tmp_name'];

        $div = explode('.', $file_name);
        $file_ext = strtolower(end($div));
        $unique_image = substr(md5(time()), 0, 10) . '.' . $file_ext;
        $uploaded_image = APP_ROOT . "../../public/images/" . $unique_image;
        move_uploaded_file($file_temp, $uploaded_image);

        $file_name2 = $_FILES['image2']['name'];
        $file_temp2 = $_FILES['image2']['tmp_name'];

        $div2 = explode('.', $file_name2);
        $file_ext2 = strtolower(end($div2));
        $unique_image2 = substr(md5(time() . '2'), 0, 10) . '.' . $file_ext2;
        $uploaded_image2 = APP_ROOT . "../../public/images/" . $unique_image2;
        move_uploaded_file($file_temp2, $uploaded_image2);

        $file_name3 = $_FILES['image3']['name'];
        $file_temp3 = $_FILES['image3']['tmp_name'];

        $div3 = explode('.', $file_name3);
        $file_ext3 = strtolower(end($div3));
        $unique_image3 = substr(md5(time() . '3'), 0, 10) . '.' . $file_ext3;
        $uploaded_image3 = APP_ROOT . "../../public/images/" . $unique_image3;
        move_uploaded_file($file_temp3, $uploaded_image3);


        $sql = "INSERT INTO `products` (`id`, `name`, `originalPrice`, `promotionPrice`, `image`,`image2`,`image3`, `createdBy`, `createdDate`, `cateId`, `qty`, `des`, `status`, `soldCount`,`weight`) VALUES (NULL, '" . $product['name'] . "', " . $product['originalPrice'] . ", " . $product['promotionPrice'] . ", '" . $unique_image . "', '" . $unique_image2 . "', '" . $unique_image3 . "', " . $_SESSION['user_id'] . ", '" . date("y-m-d H:i:s") . "', " . $product['cateId'] . ", " . $product['qty'] . ", '" . $product['des'] . "', 1, 0, " . $product['weight'] . ")";
        $result = mysqli_query($db->con, $sql);
        // file_get_contents("http://localhost:8983/solr/products/dataimport?command=full-import");
        return $result;
    }

    public function update($product)
    {
        // Check image and move to upload folder
        if (!empty($_FILES['image']['name'])) {
            $file_name = $_FILES['image']['name'];
            $file_temp = $_FILES['image']['tmp_name'];

            $div = explode('.', $file_name);
            $file_ext = strtolower(end($div));
            $unique_image = substr(md5(time() . '1'), 0, 10) . '.' . $file_ext;
            $uploaded_image = APP_ROOT . "../../public/images/" . $unique_image;

            move_uploaded_file($file_temp, $uploaded_image);
        }


        // Check image and move to upload folder
        if (!empty($_FILES['image2']['name'])) {
            $file_name = $_FILES['image2']['name'];
            $file_temp = $_FILES['image2']['tmp_name'];

            $div = explode('.', $file_name);
            $file_ext = strtolower(end($div));
            $unique_image2 = substr(md5(time() . '2'), 0, 10) . '.' . $file_ext;
            $uploaded_image2 = APP_ROOT . "../../public/images/" . $unique_image2;

            move_uploaded_file($file_temp, $uploaded_image2);
        }

        // Check image and move to upload folder
        if (!empty($_FILES['image3']['name'])) {
            $file_name = $_FILES['image3']['name'];
            $file_temp = $_FILES['image3']['tmp_name'];

            $div = explode('.', $file_name);
            $file_ext = strtolower(end($div));
            $unique_image3 = substr(md5(time() . '3'), 0, 10) . '.' . $file_ext;
            $uploaded_image3 = APP_ROOT . "../../public/images/" . $unique_image3;

            move_uploaded_file($file_temp, $uploaded_image3);
        }

        $db = DB::getInstance();
        $sql = "UPDATE `products` SET name = '" . $_POST['name'] . "', `originalPrice` = " . $_POST['originalPrice'] . ", `promotionPrice` = " . $_POST['promotionPrice'];
        if (!empty($_FILES['image']['name'])) {
            $sql .=  ", `image` = '" . $unique_image . "'";
        }
        if (!empty($_FILES['image2']['name'])) {
            $sql .=  ", `image2` = '" . $unique_image2 . "'";
        }
        if (!empty($_FILES['image3']['name'])) {
            $sql .=  ", `image3` = '" . $unique_image3 . "'";
        }
        $sql .= ", `cateId` = " . $_POST['cateId'] . ", `des` = '" . $_POST['des'] . "', `weight` = " . $_POST['weight'] . " WHERE id = " . $_POST['id'] . "";
        $result = mysqli_query($db->con, $sql);
        // file_get_contents("http://localhost:8983/solr/products/dataimport?command=full-import");
        return $result;
    }

    public function getCountPaging($row = 8)
    {
        $db = DB::getInstance();
        $sql = "SELECT COUNT(*) FROM products";
        $result = mysqli_query($db->con, $sql);
        if ($result) {
            $totalrow = intval((mysqli_fetch_all($result, MYSQLI_ASSOC)[0])['COUNT(*)']);
            return ceil($totalrow / $row);
        }
        return false;
    }

    public function getCountPagingByClient($cateId, $row = 8)
    {
        $db = DB::getInstance();
        $sql = "SELECT COUNT(*) FROM products WHERE cateId = $cateId AND status=1";
        $result = mysqli_query($db->con, $sql);
        if ($result) {
            $totalrow = intval((mysqli_fetch_all($result, MYSQLI_ASSOC)[0])['COUNT(*)']);
            return ceil($totalrow / $row);
        }
        return false;
    }

    public function getSoldCountMonth()
    {
        $db = DB::getInstance();
        $sql = "SELECT SUM(p.soldCount) AS total, p.name FROM `orders` o JOIN order_details od ON o.id  JOIN products p ON od.productId = p.id WHERE MONTH(o.createdDate) = MONTH(NOW()) AND o.paymentStatus=1 GROUP BY p.id, MONTH(o.createdDate), YEAR(o.createdDate)";
        $result = mysqli_query($db->con, $sql);
        return $result;
    }
}
