<?php require APP_ROOT . '/views/client/inc/head.php'; ?>

<body>
  <?php
  $cart = new cart();
  if (!isset($_SESSION['cart'])) {
    $total = (isset($cart->getTotalQuantitycart()['total']) ? $cart->getTotalQuantitycart()['total'] : 0);
  } else {
    $total = $cart->getTotal();
  }

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
              <li><a href="<?= URL_ROOT . '/product/category/' . $key['id'] ?>?page=1"><?= $key['name'] ?></a></li>
            <?php }
            ?>
          </ul>
        </li>
        <li><a href="<?= URL_ROOT . "/blog" ?>">Blog <i class="fa fa-book"></i></a></li>

        <?php
        if (isset($_SESSION['user_id'])) { ?>
          <li class="cate">
            <a href="#"><?= $_SESSION['user_name'] ?> <i class="fa fa-user-circle"></i></a>
            <ul class="sub-menu">
              <li><a href="<?= URL_ROOT . "/user/info" ?>">Thông tin tài khoản <i class="fa fa-user"></i></a></li>
              <li><a href="<?= URL_ROOT . "/order/checkout" ?>">Đơn hàng của tôi <i class="fa fa-list-alt"></i></a></li>
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
  <?php require_once APP_ROOT . '/core/Config.php'; ?>
  <div class="banner">

  </div>
  <div class="title">Thông báo</div>
  <?php
  ?>
  <div class="login">
    <div class="login-container">
      <div>
        <label>Kết quả:</label>
        <label>
          <?php
          if (isset($_GET['vnp_ResponseCode']) && $_GET['vnp_ResponseCode'] == '00') {
            unset($_SESSION['cart']);
            unset($_SESSION['voucher']);
            $cart = new cart();
            $cart->deleteCart();
            echo "<h2 style='color:green'>Thanh toán thành công qua VNPay</h2>"; ?>
            <a href="<?= URL_ROOT . '/order/detail/' . $_GET['orderId'] ?>">Xem đơn hàng</a>
            <?php
          } else if (isset($_GET['resultCode']) && $_GET['resultCode'] == "0") {
            unset($_SESSION['cart']);
            unset($_SESSION['voucher']);
            $cart = new cart();
            $cart->deleteCart();
            echo "<h2 style='color:green'>Thanh toán thành công qua Momo</h2>"; ?>
              <a href="<?= URL_ROOT . '/order/detail/' . $_GET['orderId'] ?>">Xem đơn hàng</a>
            <?php
          } else {
            $order = new Order();
            $order->delete($_GET['orderId']);
            echo "<h2 style='color:red'>Thanh toán thất bại</h2>"; ?>
              <a href="<?= URL_ROOT . '/cart/checkout' ?>">Đặt hàng lại</a>
            <?php
          }
          ?>
        </label>
      </div>
    </div>
  </div>

  <?php require APP_ROOT . '/views/client/inc/footer.php'; ?>
</body>

</html>