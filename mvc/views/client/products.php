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
    <div class="logo">COSMETIC STORE</div>
    <div class="search-container">
      <form action="<?= URL_ROOT ?>/product/search" method="get">
        <input type="text" class="search" placeholder="Tìm kiếm.." name="keyword"
          value="<?= isset($_GET['keyword']) ? $_GET['keyword'] : "" ?>">
        <button type="submit"><i class="fa fa-search"></i></button>
      </form>
    </div>
    <ul class="nav-links">
      <input type="checkbox" id="checkbox_toggle" />
      <label for="checkbox_toggle" class="hamburger">&#9776;</label>
      <div class="menu">
        <li><a href="<?= URL_ROOT ?>">Trang chủ <i class="fa fa-home"></i></a></li>
        <li><a href="<?= URL_ROOT ?>/home/about">Giới thiệu <i class="fa fa-info"></i></a></li>
        <li class="cate menu-active">
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
  <div class="banner">

  </div>
  <div class="title"><?= $data['title'] ?></div>
  <div class="content">
    <?php
    if ($data['productList']) {
      foreach ($data['productList'] as $key => $value) { ?>
            <div class="card">
              <?php
              if ($value['promotionPrice'] < $value['originalPrice']) { ?>
                  <div class="discount">
                    -<?= ceil(100 - (($value['promotionPrice'] / $value['originalPrice'] * 100))) ?>%
                  </div>
              <?php }
              ?>
              <div class="card-img">
                <a href="<?= URL_ROOT . '/product/single/' . $value['id'] ?>"><img
                    src="<?= URL_ROOT ?>/public/images/<?= $value['image'] ?>" class="product-image" alt=""></a>
              </div>
              <a href="<?= URL_ROOT . '/product/single/' . $value['id'] ?>">
                <h1><?= $value['name'] ?></h1>
              </a>
              <?php
              if ($value['promotionPrice'] < $value['originalPrice']) { ?>
                  <p class="promotion-price"><del><?= number_format($value['originalPrice'], 0, '', ',') ?>₫</del></p>
              <?php }
              ?>
              <p class="original-price"><?= number_format($value['promotionPrice'], 0, '', ',') ?>₫</p>
              <p class="qty-card">Kho: <?= $value['qty'] ?></p>
              <p class="sold-count">Đã bán: <?= $value['soldCount'] ?></p>
              <p><a href="<?= URL_ROOT . '/cart/addItemcart/' . $value['id'] ?>"><button>Thêm vào giỏ</button></a></p>
            </div>
        <?php }
    } else { ?>
        <h3>Không tìm thấy sản phẩm...</h3>
    <?php }
    ?>
  </div>
  <?php require APP_ROOT . '/views/client/inc/footer.php'; ?>
</body>

</html>