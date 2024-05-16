<?php require APP_ROOT . '/views/admin/inc/head.php'; ?>

<body>
    <?php require APP_ROOT . '/views/admin/inc/sidebar.php'; ?>

    <div class="main-content">

        <main>
            <section class="recent">
                <div class="activity-grid">
                    <div class="activity-card">
                        <h3>Đơn hàng: <?= $data['orderId'] ?></h3>
                        <div class="table-responsive">
                            <table>
                                <thead>
                                    <tr>
                                        <th>STT</th>
                                        <th>Tên sản phẩm</th>
                                        <th>Hình ảnh</th>
                                        <th>Số lượng</th>
                                        <th>Đơn giá</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $count = 0;
                                    foreach ($data['orderDetailList'] as $key => $value) {
                                    ?>
                                        <tr>
                                            <td><?= ++$count ?></td>
                                            <td><?= $value['productName'] ?></td>
                                            <td><img class="img-table" src="<?= URL_ROOT . '/public/images/' . $value['productImage'] ?>" alt=""></td>
                                            <td><?= $value['qty'] ?></td>
                                            <td><?= number_format($value['productPrice'], 0, '', ',') ?>₫</td>
                                        </tr>
                                    <?php }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                        <?php
                        if ($data["order"]['status'] == "processing") { ?>
                            <a href="<?= URL_ROOT . '/orderManage/processed/' . $data['orderId'] ?>" class="button right">Xác nhận</a>
                        <?php } elseif ($data["order"]['status'] == "processed") { ?>
                            <a href="<?= URL_ROOT . '/orderManage/delivery/' . $data['orderId'] ?>" class="button right">Giao hàng</a>
                        <?php }
                        ?>
                    </div>
                </div>
            </section>

        </main>

    </div>
</body>

</html>