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
  <div class="title">Sản phẩm</div>
  <main class="container">
    <div class="left-column">
      <img id="img1" class="image-product show" src="<?= URL_ROOT ?>/public/images/<?= $data['product']['image'] ?>"
        alt="">
      <img id="img2" class="image-product" src="<?= URL_ROOT ?>/public/images/<?= $data['product']['image2'] ?>" alt="">
      <img id="img3" class="image-product" src="<?= URL_ROOT ?>/public/images/<?= $data['product']['image3'] ?>" alt="">
      <button id="a" class="btn-show show-image">1</button>
      <button id="b" class="btn-show">2</button>
      <button id="c" class="btn-show">3</button>
    </div>
    <div class="right-column">
      <div class="product-description">
        <h1><?= $data['product']['name'] ?></h1>
        Đánh giá:
        <?php
        if ($data['star'] > 0) {
          for ($i = 0; $i < $data['star']; $i++) { ?>
            <i class="fa fa-star" style="color: orange;"></i>
          <?php }
          echo $data['star'];
          ?>/5
        <?php } else { ?>
          Chưa có đánh giá
        <?php } ?> <br>
        <p><?= $data['product']['des'] ?></p>
      </div>
      <div class="product-price">
        <?php
        if ($data['product']['promotionPrice'] < $data['product']['originalPrice']) { ?>
          <span><?= number_format($data['product']['promotionPrice'], 0, '', ',') ?>₫</span>
          <del><?= number_format($data['product']['originalPrice'], 0, '', ',') ?>₫</del>
          <p class="error">
            -<?= ceil(100 - (($data['product']['promotionPrice'] / $data['product']['originalPrice'] * 100))) ?>%</p>
        <?php } else { ?>
          <span><?= number_format($data['product']['originalPrice'], 0, '', ',') ?>₫</span>
        <?php }
        ?>
        <a href="<?= URL_ROOT . '/cart/addItemcart/' . $data['product']['id'] ?>" class="cart-btn">Thêm vào giỏ</a>
      </div>
    </div>
  </main>
  <div class="title2">Đánh giá</div>
  <div class="rating">
    <?php
    if (count($data['productRatingContent']) > 0) {
      foreach ($data['productRatingContent'] as $key => $value) { ?>
        <div class="rate">
          <div class="user-name">
            Khách hàng <b><?= $value['fullName'] ?> </b> (<?= date("d/m/Y", strtotime($value['createdDate'])) ?>)
          </div>
          <div class="user-star">
            <?php
            for ($i = 0; $i < $value['star']; $i++) { ?>
              <i class="fa fa-star" style="color: orange;"></i>
            <?php }
            ?>
          </div>
          <div class="user-content">
            <?= $value['content'] ?>
          </div>
          <?php
          if ($value['repliedDate']) { ?>
            <div class="reply">
              <div class="user-name">
                <i class="fa fa-reply" aria-hidden="true"></i>
                Phản hồi từ <b>Quản trị viên</b> (<?= date("d/m/Y", strtotime($value['repliedDate'])) ?>)
              </div>
              <div class="user-content">
                <?= $value['reply'] ?>
              </div>
            </div>
          <?php }
          ?>
        </div>
      <?php }
    } else { ?>
      <div class="rate">
        Chưa có đánh giá
      </div>
    <?php } ?>
  </div>
  <div class="title2">Hỏi đáp</div>
  <div class="rating">
    <?php
    if (isset($_SESSION['user_id'])) { ?>
      <div class="rate">
        <b>Gửi câu hỏi</b>
        <div class="search-container">
          <form action="<?= URL_ROOT ?>/product/addQuestion" method="post">
            <input type="hidden" name="productId" value="<?= $data['product']['id'] ?>">
            <input type="text" class="search" placeholder="Nhập vào câu hỏi..." name="content">
            <button type="submit">Gửi</button>
          </form>
        </div>
      </div>
    <?php } else { ?>
      <div class="rate">
        <a href="<?= URL_ROOT ?>/user/login">Đăng nhập để gửi câu hỏi</a>
      </div>
    <?php }
    ?>
    <?php
    if (count($data['questionContent']) > 0) {
      foreach ($data['questionContent'] as $key => $value) { ?>
        <div class="rate">
          <div class="user-name">
            Khách hàng <b><?= $value['fullName'] ?> </b> (<?= date("d/m/Y", strtotime($value['createdDate'])) ?>)
          </div>
          <div class="user-content">
            <?= $value['content'] ?>
          </div>
          <?php
          if ($value['repliedDate']) { ?>
            <div class="reply">
              <div class="user-name">
                <i class="fa fa-reply" aria-hidden="true"></i>
                Phản hồi từ <b>Quản trị viên</b> (<?= date("d/m/Y", strtotime($value['repliedDate'])) ?>)
              </div>
              <div class="user-content">
                <?= $value['reply'] ?>
              </div>
            </div>
          <?php }
          ?>
        </div>
      <?php }
    } else { ?>
      <div class="rate">
        Chưa có hỏi đáp
      </div>
    <?php } ?>
  </div>
  <div class="title2">Sản phẩm gợi ý</div>
  <div class="content">
    <?php
    if (count($data['productSuggest']) > 0) {
      foreach ($data['productSuggest'] as $key) { ?>
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
      <h3>Không có sản phẩm...</h3>
    <?php }
    ?>
  </div>
  <div class="title2">Bài viết liên quan</div>
  <div class="content">
    <?php
    if (count($data['blogList']) > 0) {
      foreach ($data['blogList'] as $key) { ?>
        <a href="<?= URL_ROOT . '/blog/detail/' . $key['id'] ?>">
          <h3><?= $key['title'] ?></h3>
        </a>
      <?php }
    } else { ?>
      <h3>Không có bài viết...</h3>
    <?php }
    ?>
  </div>
  <?php require APP_ROOT . '/views/client/inc/footer.php'; ?>
  <script>
    var btns = document.getElementsByClassName("btn-show");
    for (var i = 0; i < btns.length; i++) {
      (function (index) {
        btns[index].addEventListener("click", function () {
          var btnRemove = document.getElementsByClassName("btn-show");
          for (var i = 0; i < btnRemove.length; i++) {
            (function (index) {
              btnRemove[index].classList.remove("show-image");
            })(i);
          }
          if (index == 0) {
            document.getElementById("a").classList.add("show-image");
          } else if (index == 1) {
            document.getElementById("b").classList.add("show-image");
          } else {
            document.getElementById("c").classList.add("show-image");
          }

          var images = document.getElementsByClassName("image-product");
          for (var i = 0; i < images.length; i++) {
            (function (index) {
              images[index].classList.remove("show");
            })(i);
          }
          if (index == 0) {
            document.getElementById("img1").classList.add("show");
          } else if (index == 1) {
            document.getElementById("img2").classList.add("show");
          } else {
            document.getElementById("img3").classList.add("show");
          }
        })
      })(i);
    }
  </script>
</body>

</html>