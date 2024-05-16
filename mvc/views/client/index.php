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
        <li class="menu-active"><a href="<?= URL_ROOT ?>">Trang chủ <i class="fa fa-home"></i></a></li>

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
        <!--  -->

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
  <!-- <div class="banner">

    </div> -->
  <!-- Slideshow container -->
  <div class="slideshow-container">

    <!-- Full-width images with number and caption text -->
    <div class="mySlides fade">
      <div class="numbertext">1 / 3</div>
      <img src="<?= URL_ROOT ?>/public/images/banner3.jpg" style="width:100%;">
    </div>

    <div class="mySlides fade">
      <div class="numbertext">2 / 3</div>
      <img src="<?= URL_ROOT ?>/public/images/banner2.png" style="width:100%;">
    </div>

    <div class="mySlides fade">
      <div class="numbertext">3 / 3</div>
      <img src="<?= URL_ROOT ?>/public/images/banner.png" style="width:100%;">
    </div>

    <!-- Next and previous buttons -->
    <a class="prev" onclick="plusSlides(-1)">&#10094;</a>
    <a class="next" onclick="plusSlides(1)">&#10095;</a>
  </div>
  <br>

  <!-- The dots/circles -->
  <div style="text-align:center">
    <span class="dot" onclick="currentSlide(1)"></span>
    <span class="dot" onclick="currentSlide(2)"></span>
    <span class="dot" onclick="currentSlide(3)"></span>
  </div>

  <div class="title">Sản phẩm nổi bật</div>

  <div class="content">
    <?php
    if (count($data['FeaturedproductsList']) > 0) {
      foreach ($data['FeaturedproductsList'] as $key) { ?>
        <div class="card">
          <?php
          if ($key['promotionPrice'] < $key['originalPrice']) { ?>
            <div class="discount">
              -<?= ceil(100 - (($key['promotionPrice'] / $key['originalPrice'] * 100))) ?>%
            </div>
          <?php }
          ?>
          <div class="card-img">
            <a href="<?= URL_ROOT . '/product/single/' . $key['id'] ?>"><img
                src="<?= URL_ROOT ?>/public/images/<?= $key['image'] ?>" class="product-image" alt=""></a>
          </div>
          <a href="<?= URL_ROOT . '/product/single/' . $key['id'] ?>">
            <h1><?= $key['name'] ?></h1>
          </a>
          <?php
          if ($key['promotionPrice'] < $key['originalPrice']) { ?>
            <p class="promotion-price"><del><?= number_format($key['originalPrice'], 0, '', ',') ?>₫</del></p>
          <?php }
          ?>
          <p class="original-price"><?= number_format($key['promotionPrice'], 0, '', ',') ?>₫</p>
          <p class="qty-card">Kho: <?= $key['qty'] ?></p>
          <p class="sold-count">Đã bán: <?= $key['soldCount'] ?></p>
          <p><a href="<?= URL_ROOT . '/cart/addItemcart/' . $key['id'] ?>"><button>Thêm vào giỏ</button></a></p>
        </div>
      <?php }
    } else { ?>
      <h3>Không tìm thấy sản phẩm...</h3>
    <?php }
    ?>


  </div>

  <div class="title">Sản phẩm mới</div>

  <div class="content">
    <?php
    if (count($data['NewproductsList']) > 0) {
      foreach ($data['NewproductsList'] as $key) { ?>
        <div class="card">
          <?php
          if ($key['promotionPrice'] < $key['originalPrice']) { ?>
            <div class="discount">
              -<?= ceil(100 - (($key['promotionPrice'] / $key['originalPrice'] * 100))) ?>%
            </div>
          <?php }
          ?>
          <div class="card-img">
            <a href="<?= URL_ROOT . '/product/single/' . $key['id'] ?>"><img
                src="<?= URL_ROOT ?>/public/images/<?= $key['image'] ?>" class="product-image" alt=""></a>
          </div>
          <a href="<?= URL_ROOT . '/product/single/' . $key['id'] ?>">
            <h1><?= $key['name'] ?></h1>
          </a>
          <?php
          if ($key['promotionPrice'] < $key['originalPrice']) { ?>
            <p class="promotion-price"><del><?= number_format($key['originalPrice'], 0, '', ',') ?>₫</del></p>
          <?php }
          ?>
          <p class="original-price"><?= number_format($key['promotionPrice'], 0, '', ',') ?>₫</p>
          <p class="qty-card">Kho: <?= $key['qty'] ?></p>
          <p class="sold-count">Đã bán: <?= $key['soldCount'] ?></p>
          <p><a href="<?= URL_ROOT . '/cart/addItemcart/' . $key['id'] ?>"><button>Thêm vào giỏ</button></a></p>
        </div>
      <?php }
    } else { ?>
      <h3>Không tìm thấy sản phẩm...</h3>
    <?php }
    ?>

  </div>

  <div class="title">Đang khuyến mãi</div>

  <div class="content">
    <?php
    if (count($data['DiscountproductsList']) > 0) {
      foreach ($data['DiscountproductsList'] as $key) { ?>
        <div class="card">
          <?php
          if ($key['promotionPrice'] < $key['originalPrice']) { ?>
            <div class="discount">
              -<?= ceil(100 - (($key['promotionPrice'] / $key['originalPrice'] * 100))) ?>%
            </div>
          <?php }
          ?>
          <div class="card-img">
            <a href="<?= URL_ROOT . '/product/single/' . $key['id'] ?>"><img
                src="<?= URL_ROOT ?>/public/images/<?= $key['image'] ?>" class="product-image" alt=""></a>
          </div>
          <a href="<?= URL_ROOT . '/product/single/' . $key['id'] ?>">
            <h1><?= $key['name'] ?></h1>
          </a>
          <?php
          if ($key['promotionPrice'] < $key['originalPrice']) { ?>
            <p class="promotion-price"><del><?= number_format($key['originalPrice'], 0, '', ',') ?>₫</del></p>
          <?php }
          ?>
          <p class="original-price"><?= number_format($key['promotionPrice'], 0, '', ',') ?>₫</p>
          <p class="qty-card">Kho: <?= $key['qty'] ?></p>
          <p class="sold-count">Đã bán: <?= $key['soldCount'] ?></p>
          <p><a href="<?= URL_ROOT . '/cart/addItemcart/' . $key['id'] ?>"><button>Thêm vào giỏ</button></a></p>
        </div>
      <?php }
    } else { ?>
      <h3>Không tìm thấy sản phẩm...</h3>
    <?php }
    ?>
  </div>


  <script>
    let slideIndex = 1;
    showSlides(slideIndex);

    // Next/previous controls
    function plusSlides(n) {
      showSlides(slideIndex += n);
    }

    // Thumbnail image controls
    function currentSlide(n) {
      showSlides(slideIndex = n);
    }

    function showSlides(n) {
      let i;
      let slides = document.getElementsByClassName("mySlides");
      let dots = document.getElementsByClassName("dot");
      if (n > slides.length) {
        slideIndex = 1
      }
      if (n < 1) {
        slideIndex = slides.length
      }
      for (i = 0; i < slides.length; i++) {
        slides[i].style.display = "none";
      }
      for (i = 0; i < dots.length; i++) {
        dots[i].className = dots[i].className.replace(" active", "");
      }
      slides[slideIndex - 1].style.display = "block";
      dots[slideIndex - 1].className += " active";
    }
  </script>
  <?php require APP_ROOT . '/views/client/inc/footer.php'; ?>
</body>

</html>