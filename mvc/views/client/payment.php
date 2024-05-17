<?php require APP_ROOT . '/views/client/inc/head.php';
require_once APP_ROOT . '/core/Config.php';
?>

<body>
    <?php
    $cart  = new cart();
    $total = (isset($cart->getTotalQuantitycart()['total']) ? $cart->getTotalQuantitycart()['total'] : 0);

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
    <div class="title">Thanh toán</div>
    <div class="table-responsive login">
        <form id="form-order" class="login-container" action="<?= URL_ROOT ?>/order/payment/<?= $data['order']['id'] ?>"
            id="create_form" method="post">
            <h3>Tạo mới đơn hàng</h3>
            <p>
                <label for="order_type">Loại hàng hóa </label>
                <select name="order_type" id="order_type" class="form-control">
                    <option value="topup">Nạp tiền điện thoại</option>
                    <option value="billpayment">Thanh toán hóa đơn</option>
                    <option value="fashion">Thời trang</option>
                    <option value="other">Khác - Xem thêm tại VNPAY</option>
                </select>
            </p>
            <p>
                <label for="order_id">Mã hóa đơn</label>
                <input type="text" id="order_id" name="order_id" placeholder="Mã hóa đơn" readonly
                    value="<?= $data['order']['id'] ?>" required>
            </p>
            <p>
                <label for="amount">Số tiền</label>
                <input type="number" id="amount" name="amount" placeholder="Số tiền" readonly
                    value="<?= $data['order']['total'] ?>" required>
            </p>
            <p>
                <label for="order_desc">Nội dung thanh toán</label>
                <textarea cols="20" id="order_desc" name="order_desc" rows="2">Noi dung thanh toan</textarea>
            </p>
            <p>
                <label for="bank_code">Ngân hàng</label>
                <select name="bank_code" id="bank_code">
                    <option value="">Không chọn</option>
                    <option value="NCB"> Ngan hang NCB</option>
                    <option value="AGRIBANK"> Ngan hang Agribank</option>
                    <option value="SCB"> Ngan hang SCB</option>
                    <option value="SACOMBANK">Ngan hang SacomBank</option>
                    <option value="EXIMBANK"> Ngan hang EximBank</option>
                    <option value="MSBANK"> Ngan hang MSBANK</option>
                    <option value="NAMABANK"> Ngan hang NamABank</option>
                    <option value="VNMART"> Vi dien tu VnMart</option>
                    <option value="VIETINBANK">Ngan hang Vietinbank</option>
                    <option value="VIETCOMBANK"> Ngan hang VCB</option>
                    <option value="HDBANK">Ngan hang HDBank</option>
                    <option value="DONGABANK"> Ngan hang Dong A</option>
                    <option value="TPBANK"> Ngân hàng TPBank</option>
                    <option value="OJB"> Ngân hàng OceanBank</option>
                    <option value="BIDV"> Ngân hàng BIDV</option>
                    <option value="TECHCOMBANK"> Ngân hàng Techcombank</option>
                    <option value="VPBANK"> Ngan hang VPBank</option>
                    <option value="MBBANK"> Ngan hang MBBank</option>
                    <option value="ACB"> Ngan hang ACB</option>
                    <option value="OCB"> Ngan hang OCB</option>
                    <option value="IVB"> Ngan hang IVB</option>
                    <option value="VISA"> Thanh toan qua VISA/MASTER</option>
                </select>
            </p>
            <p>
                <label for="language">Ngôn ngữ</label>
                <select name="language" id="language">
                    <option value="vn">Tiếng Việt</option>
                    <option value="en">English</option>
                </select>
            </p>
            <p>
                <label for="txtexpire">Thời hạn thanh toán</label>
                <input type="text" id="txtexpire" name="txtexpire" placeholder="Thời hạn thanh toán" readonly
                    value="<?php echo date('YmdHis', strtotime('+15 minutes', strtotime(date("YmdHis")))); ?>" required>
            </p>
            <h3>Thông tin hóa đơn (Billing)</h3>
            <p>
                <label for="txt_billing_fullname">Họ tên (*)</label>
                <input type="text" id="txt_billing_fullname" name="txt_billing_fullname" placeholder="Họ tên" readonly
                    value="<?= $data['user']['fullName'] ?>" required>
            </p>
            <p>
                <label for="txt_billing_email">Email (*)</label>
                <input type="text" id="txt_billing_email" name="txt_billing_email" placeholder="Email" readonly
                    value="<?= $data['user']['email'] ?>" required>
            </p>
            <p>
                <label for="txt_billing_addr1">Địa chỉ (*)</label>
                <input type="text" id="txt_billing_addr1" name="txt_billing_addr1" placeholder="Địa chỉ" readonly
                    value="<?= $data['user']['address'] ?>" required>
            </p>
            <p>
                <label for="txt_billing_mobile">Số điện thoại (*)</label>
                <input type="text" id="txt_billing_mobile" name="txt_billing_mobile" placeholder="Số điện thoại"
                    readonly value="<?= $data['user']['phone'] ?>" required>
            </p>
            <p>
                <label for="txt_postalcode">Mã bưu điện (*)</label>
                <input type="text" id="txt_postalcode" name="txt_postalcode" placeholder="Mã bưu điện" readonly
                    value="100000" required>
            </p>
            <p>
                <label for="txt_bill_city">Tỉnh/TP (*)</label>
                <input type="text" id="txt_bill_city" name="txt_bill_city" placeholder="Tỉnh/TP" readonly
                    value="Cần Thơ" required>
            </p>
            <p>
                <label for="txt_bill_state">Bang (Áp dụng cho US,CA)</label>
                <input type="text" id="txt_bill_state" name="txt_bill_state" placeholder="Bang (Áp dụng cho US,CA)">
            </p>
            <p>
                <label for="txt_bill_country">Quốc gia (*)</label>
                <input type="text" id="txt_bill_country" name="txt_bill_country" placeholder="Quốc gia" value="VN"
                    required>
            </p>
            <h3>Thông tin giao hàng (Shipping)</h3>
            <p>
                <label for="txt_ship_fullname">Họ tên (*)</label>
                <input type="text" id="txt_ship_fullname" name="txt_ship_fullname" placeholder="Họ tên"
                    value="Nguyễn Thế Vinh" required>
            </p>
            <p>
                <label for="txt_ship_email">Email (*)</label>
                <input type="text" id="txt_ship_email" name="txt_ship_email" placeholder="Email" value="vinhnt@vnpay.vn"
                    required>
            </p>
            <p>
                <label for="txt_ship_mobile">Số điện thoại (*)</label>
                <input type="text" id="txt_ship_mobile" name="txt_ship_mobile" placeholder="Số điện thoại"
                    value="0123456789" required>
            </p>
            <p>
                <label for="txt_ship_addr1">Địa chỉ (*)</label>
                <input type="text" id="txt_ship_addr1" name="txt_ship_addr1" placeholder="Địa chỉ"
                    value="Phòng 315, Công ty VNPAY, Tòa nhà TĐL, 22 Láng Hạ, Đống Đa, Hà Nội" required>
            </p>
            <p>
                <label for="txt_ship_postalcode">Mã bưu điện (*)</label>
                <input type="text" id="txt_ship_postalcode" name="txt_ship_postalcode" placeholder="Mã bưu điện"
                    value="1000000" required>
            </p>
            <p>
                <label for="txt_ship_city">Tỉnh/TP (*)</label>
                <input type="text" id="txt_ship_city" name="txt_ship_city" placeholder="Tỉnh/TP" value="Hà Nội"
                    required>
            </p>
            <p>
                <label for="txt_ship_state">Bang (Áp dụng cho US,CA)</label>
                <input type="text" id="txt_ship_state" name="txt_ship_state" placeholder="Bang (Áp dụng cho US,CA)"
                    value="Hà Nội">
            </p>
            <p>
                <label for="txt_ship_country">Quốc gia</label>
                <input type="text" id="txt_ship_country" name="txt_ship_country" placeholder="Quốc gia" value="VN"
                    required>
            </p>
            <h3>Thông tin gửi Hóa đơn điện tử (Invoice)</h3>
            <p>
                <label for="txt_inv_customer">Tên khách hàng</label>
                <input type="text" id="txt_inv_customer" name="txt_inv_customer" placeholder="Tên khách hàng"
                    value="Lê Văn Phổ" required>
            </p>
            <p>
                <label for="txt_inv_company">Công ty</label>
                <input type="text" id="txt_inv_company" name="txt_inv_company" placeholder="Công ty"
                    value="Công ty Cổ phần giải pháp Thanh toán Việt Nam" required>
            </p>
            <p>
                <label for="txt_inv_addr1">Địa chỉ</label>
                <input type="text" id="txt_inv_addr1" name="txt_inv_addr1" placeholder="Địa chỉ"
                    value="22 Láng Hạ, Phường Láng Hạ, Quận Đống Đa, TP Hà Nội" required>
            </p>
            <p>
                <label for="txt_inv_taxcode">Mã số thuế</label>
                <input type="text" id="txt_inv_taxcode" name="txt_inv_taxcode" placeholder="Mã số thuế"
                    value="0102182292" required>
            </p>
            <p>
                <label>Loại hóa đơn</label>
                <select name="cbo_inv_type" id="cbo_inv_type">
                    <option value="I">Cá Nhân</option>
                    <option value="O">Công ty/Tổ chức</option>
                </select>
            </p>
            <p>
                <label for="txt_inv_email">Email</label>
                <input type="text" id="txt_inv_email" name="txt_inv_email" placeholder="Email" value="pholv@vnpay.vn"
                    required>
            </p>
            <p>
                <label for="txt_inv_mobile">Điện thoại</label>
                <input type="text" id="txt_inv_mobile" name="txt_inv_mobile" placeholder="Điện thoại"
                    value="02437764668" required>
            </p>
            <p>
                <input type="submit" id="btnPopup" value="Thanh toán" />
            </p>
            <!-- <button type="submit" name="redirect" id="redirect" class="btn btn-default">Thanh toán Redirect</button> -->

        </form>
    </div>
    <?php require APP_ROOT . '/views/client/inc/footer.php'; ?>
    <script>
        // document.getElementById("form-order").submit();
    </script>
</body>

</html>