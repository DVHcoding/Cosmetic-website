<?php require APP_ROOT . '/views/client/inc/head.php'; ?>
<style>
  * {
    box-sizing: border-box;
  }

  /* Header/Blog Title */
  .header {
    padding: 30px;
    font-size: 40px;
    text-align: center;
    background: white;
  }

  /* Create two unequal columns that floats next to each other */
  /* Left column */
  .leftcolumn {
    float: left;
    width: 75%;
  }

  /* Right column */
  .rightcolumn {
    float: left;
    width: 25%;
    padding-left: 20px;
  }

  /* Fake image */
  .fakeimg {
    background-color: #aaa;
    width: 100%;
    padding: 20px;
  }

  /* Add a card-blog effect for articles */
  .card-blog {
    background-color: white;
    padding: 20px;
    margin-top: 20px;
  }

  /* Clear floats after the columns */
  .row:after {
    content: "";
    display: table;
    clear: both;
  }

  /* Footer */
  .footer {
    padding: 20px;
    text-align: center;
    background: #ddd;
    margin-top: 20px;
  }

  /* Responsive layout - when the screen is less than 800px wide, make the two columns stack on top of each other instead of next to each other */
  @media screen and (max-width: 800px) {

    .leftcolumn,
    .rightcolumn {
      width: 100%;
      padding: 0;
    }
  }

  .content-blog {
    width: 50%;
    margin: 20px auto;
    font-size: 20px;
  }

  img {
    margin: 20px 0px;
    width: 100%;
  }
</style>

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
        <li class="cate">
          <a href="#">Danh mục <i class="fa fa-list-ul"></i></a>
          <ul class="sub-menu">
            <?php
            foreach ($listCategory as $key => $value) { ?>
                <li><a href="<?= URL_ROOT . '/product/category/' . $value['id'] ?>?page=1"><?= $value['name'] ?></a></li>
            <?php }
            ?>
          </ul>
        </li>
        <li class=" menu-active"><a href="<?= URL_ROOT . "/blog" ?>">Blog <i class="fa fa-book"></i></a></li>

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
  <div class="title">Blog</div>
  <div class="content-blog">
    <h1><a href="<?= URL_ROOT ?>/blog/detail/<?= $data['blog']['id'] ?>"><?= $data['blog']['title'] ?></a></h1>
    <p>Ngày đăng: <?= date("d/m/Y H:m", strtotime($data['blog']['createdDate'])) ?> bỏi
      <b><?= $data['blog']['author'] ?></b> <?= $data['blog']['views'] ?> lượt xem
    </p>
    <a href="<?= URL_ROOT ?>/blog/detail/<?= $data['blog']['id'] ?>"><img
        src="<?= URL_ROOT ?>/public/images/<?= $data['blog']['image'] ?>"></a>
    <div style="height:200px;">
      <?= $data['blog']['content'] ?>
    </div>
  </div>
</body>

</html>