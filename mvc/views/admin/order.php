<?php require APP_ROOT . '/views/admin/inc/head.php'; ?>

<body>
    <?php require APP_ROOT . '/views/admin/inc/sidebar.php'; ?>

    <div class="main-content">
        <header>
            <div class="search-wrapper">
                <form action="<?= URL_ROOT ?>/orderManage/index" method="get">
                    <input type="search" placeholder="Tìm kiếm" name="keyword">
                </form>
            </div>
        </header>



        <main>
            <section class="recent">
                <div class="activity-grid">
                    <div class="activity-card">
                        <h3>Đơn hàng</h3>
                        <div class="table-responsive">
                            <table>
                                <thead>
                                    <tr>
                                        <th>STT</th>
                                        <th>Mã HD</th>
                                        <th>Tên khách hàng</th>
                                        <th>Ngày đặt</th>
                                        <th>Tình trạng</th>
                                        <th>Phương thức thanh toán</th>
                                        <th>Trạng thái</th>
                                        <th>Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $count = 0;
                                    foreach ($data['orderList'] as $key => $value) {
                                        ?>
                                        <tr>
                                            <td><?= ++$count ?></td>
                                            <td><?= $value['orderId'] ?></td>
                                            <td><?= $value['fullName'] ?></td>
                                            <td><?= date("d/m/Y", strtotime($value['createdDate'])) ?></td>
                                            <?php
                                            if ($value['status'] == "processing") { ?>
                                                <td><span class="gray">Chưa xác nhận</span></td>
                                            <?php } else if ($value['status'] == "processed") { ?>
                                                    <td><span class="blue">Đã xác nhận</span></td>
                                            <?php } else if ($value['status'] == "delivery") { ?>
                                                        <td><span class="yellow">Đang giao hàng</span></td>
                                            <?php } else if ($value['status'] == "cancel") { ?>
                                                            <td><span class="gray">Đã hủy</span></td>
                                            <?php } else if ($value['status'] == "received") { ?>
                                                                <td><span class="active">Hoàn thành</span></td>
                                            <?php } else { ?>
                                                                <td><span class="gray">...</span></td>
                                            <?php }
                                            ?>
                                            <td><?= $value['paymentMethod'] ?></td>
                                            <?php
                                            if ($value['paymentStatus']) { ?>
                                                <td><span class="active">Đã thanh toán</span></td>
                                            <?php } else { ?>
                                                <td><span class="gray">Chưa thanh toán</span></td>
                                            <?php }
                                            ?>
                                            <td><a href="<?= URL_ROOT . '/orderManage/detail/' . $value['orderId'] ?>">Chi
                                                    tiết</a></td>
                                        </tr>
                                    <?php }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </section>

        </main>

    </div>
</body>

</html>