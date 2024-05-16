<?php require APP_ROOT . '/views/client/inc/head.php'; ?>

<body>
    <?php
    $cart  = new cart();
    $total = (isset($cart->getTotalQuantitycart()['total']) ? $cart->getTotalQuantitycart()['total'] : 0);

    $category     = $this->model("categoryModel");
    $result       = $category->getAllClient();
    $listCategory = $result->fetch_all(MYSQLI_ASSOC);
    ?>
    <nav class="navbar">
        <div class="logo">HUYPHAM STORE</div>
        <div class="search-container">
            <form action="<?= URL_ROOT ?>/product/search" method="get">
                <input type="text" class="search" placeholder="Tìm kiếm.." name="keyword">
                <button type="submit"><i class="fa fa-search"></i></button>
            </form>
        </div>
        <ul class="nav-links">
            <input type="checkbox" id="checkbox_toggle" />
            <label for="checkbox_toggle" class="hamburger">&#9776;</label>
            <div class="menu">
                <li><a href="<?= URL_ROOT ?>">Trang chủ <i class="fa fa-home"></i></a></li>
                <li><a href="<?= URL_ROOT ?>/home/about">Giới thiệu <i class="fa fa-info"></i></a></li>
                <li class="cate">
                    <a href="#">Danh mục <i class="fa fa-list-ul"></i></a>
                    <ul class="sub-menu">
                        <?php
                        foreach ($listCategory as $key) { ?>
                            <li><a href="<?= URL_ROOT . '/product/category/' . $key['id'] ?>?page=1"><?= $key['name'] ?></a>
                            </li>
                        <?php }
                        ?>
                    </ul>
                </li>

                <?php
                if (isset($_SESSION['user_id'])) { ?>
                    <li class="cate">
                        <a href="#"><?= $_SESSION['user_name'] ?> <i class="fa fa-user-circle"></i></a>
                        <ul class="sub-menu">
                            <li><a href="<?= URL_ROOT . "/user/info" ?>">Thông tin tài khoản <i class="fa fa-user"></i></a>
                            </li>
                            <li><a href="<?= URL_ROOT . "/order/checkout" ?>">Đơn hàng của tôi <i
                                        class="fa fa-list-alt"></i></a></li>
                            <li><a href="<?= URL_ROOT . "/user/logout" ?>">Đăng xuất <i class="fa fa-sign-out"></i></a></li>
                        </ul>
                    </li>
                <?php } else { ?>
                    <li><a href="<?= URL_ROOT . "/user/register" ?>">Đăng ký <i class="fa fa-pencil-square"></i></a></li>
                    <li><a href="<?= URL_ROOT . "/user/login" ?>">Đăng nhập <i class="fa fa-sign-in"></i></a></li>
                <?php }
                ?>
                <li><a href="<?= URL_ROOT . "/cart/checkout" ?>" id="bag">Giỏ hàng <i class="fa fa-shopping-bag"></i>
                        (<?= is_null($total) ? 0 : $total ?>)</a></li>
            </div>
        </ul>
    </nav>
    <div class="banner">

    </div>
    <div class="title">Thông báo</div>
    <h2><?= $data['message'] ?></h2>
    <?php

    $vnp_SecureHash = $_GET['vnp_SecureHash'];
    $inputData      = array();
    foreach ($_GET as $key => $value) {
        if (substr($key, 0, 4) == "vnp_") {
            $inputData[$key] = $value;
        }
    }

    unset($inputData['vnp_SecureHash']);
    ksort($inputData);
    $i        = 0;
    $hashData = "";
    foreach ($inputData as $key => $value) {
        if ($i == 1) {
            $hashData = $hashData . '&' . urlencode($key) . "=" . urlencode($value);
        } else {
            $hashData = $hashData . urlencode($key) . "=" . urlencode($value);
            $i        = 1;
        }
    }

    $secureHash = hash_hmac('sha512', $hashData, $vnp_HashSecret);
    ?>
    <!--Begin display -->
    <div class="container">
        <div class="header clearfix">
            <h3 class="text-muted">VNPAY RESPONSE</h3>
        </div>
        <div class="table-responsive">
            <div class="form-group">
                <label>Mã đơn hàng:</label>

                <label><?php echo $_GET['vnp_TxnRef'] ?></label>
            </div>
            <div class="form-group">

                <label>Số tiền:</label>
                <label><?php echo $_GET['vnp_Amount'] ?></label>
            </div>
            <div class="form-group">
                <label>Nội dung thanh toán:</label>
                <label><?php echo $_GET['vnp_orderInfo'] ?></label>
            </div>
            <div class="form-group">
                <label>Mã phản hồi (vnp_ResponseCode):</label>
                <label><?php echo $_GET['vnp_ResponseCode'] ?></label>
            </div>
            <div class="form-group">
                <label>Mã GD Tại VNPAY:</label>
                <label><?php echo $_GET['vnp_TransactionNo'] ?></label>
            </div>
            <div class="form-group">
                <label>Mã Ngân hàng:</label>
                <label><?php echo $_GET['vnp_BankCode'] ?></label>
            </div>
            <div class="form-group">
                <label>Thời gian thanh toán:</label>
                <label><?php echo $_GET['vnp_PayDate'] ?></label>
            </div>
            <div class="form-group">
                <label>Kết quả:</label>
                <label>
                    <?php
                    if ($secureHash == $vnp_SecureHash) {
                        if ($_GET['vnp_ResponseCode'] == '00') {
                            echo "<span style='color:blue'>GD Thanh cong</span>";
                        } else {
                            echo "<span style='color:red'>GD Khong thanh cong</span>";
                        }
                    } else {
                        echo "<span style='color:red'>Chu ky khong hop le</span>";
                    }
                    ?>

                </label>
            </div>
        </div>
        <p>
            &nbsp;
        </p>
        <footer class="footer">
            <p>&copy; VNPAY <?php echo date('Y') ?></p>
        </footer>
    </div>
    <?php require APP_ROOT . '/views/client/inc/footer.php'; ?>
</body>

</html>