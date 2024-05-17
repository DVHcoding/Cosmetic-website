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
            <li class="menu-active"><a href="<?= URL_ROOT . "/user/login" ?>">Đăng nhập <i class="fa fa-sign-in"></i></a>
            </li>
        <?php }
        ?>
        <li><a href="<?= URL_ROOT . "/cart/checkout" ?>" id="bag">Giỏ hàng <i class="fa fa-shopping-bag"></i>
            (<?= is_null($total) ? 0 : $total ?>)</a></li>
      </div>
    </ul>
  </nav>
  <div class="banner">

  </div>
  <?php
  if ($data['status']) { ?>
      <table id="table">
        <?php
        $count = 0; ?>
        <tr>
          <th>STT</th>
          <th>Tên sản phẩm</th>
          <th>Hình ảnh</th>
          <th>Số sao</th>
          <th>Nội dung</th>
        </tr>
        <?php foreach ($data['productRating'] as $key => $value) {
          ?>
            <tr>
              <td><?= ++$count ?></td>
              <td><?= $value['name'] ?></td>
              <td><img class="img-table" src="<?= URL_ROOT . '/public/images/' . $value['image'] ?>" alt=""></td>
              <td><?= $value['star'] ?></td>
              <td><?= $value['content'] ?></td>
            </tr>
        <?php }
        ?>
      </table>
  <?php } else { ?>
      <div class="login">
        <div class="login-triangle"></div>
        <h2 class="login-header">Thêm Đánh giá</h2>

        <form action="<?= URL_ROOT ?>/product/rating/<?= $data['product']['id'] ?>" class="login-container" method="post">
          <input type="hidden" name="productId" value="<?= $data['product']['id'] ?>">

          <p><input type="text" value="<?= $data['product']['name'] ?>" required disabled></p>

          <p><img src="<?= URL_ROOT ?>/public/images/<?= $data['product']['image'] ?>"></p>

          <p>Số sao:<select name="star">
              <option value="1">1</option>
              <option value="2">2</option>
              <option value="3">3</option>
              <option value="4">4</option>
              <option value="5">5</option>
            </select></p>

          <p><textarea type="text" placeholder="Nội dung" name="content" required></textarea></p>

          <p class="error"><?= isset($data['message']) ? $data['message'] : "" ?></p>

          <p><input type="submit" value="Đánh giá ngay"></p>
        </form>
      </div>
  <?php }
  ?>
  <?php require APP_ROOT . '/views/client/inc/footer.php'; ?>
</body>

</html>