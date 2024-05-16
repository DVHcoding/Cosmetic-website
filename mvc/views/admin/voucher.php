<?php require APP_ROOT . '/views/admin/inc/head.php'; ?>

<body>
    <?php require APP_ROOT . '/views/admin/inc/sidebar.php'; ?>

    <div class="main-content">

        <main>
            <section class="recent">
                <div class="activity-grid">
                    <div class="activity-card">
                        <a href="<?= URL_ROOT . '/voucherManage/add' ?>" class="button right">Thêm mới</a>
                        <h3>Danh sách Voucher</h3>
                        <div class="table-responsive">
                            <table>
                                <thead>
                                    <tr>
                                        <th>STT</th>
                                        <th>Code</th>
                                        <th>Phần trăm giảm</th>
                                        <th>Số lượng</th>
                                        <th>Đã dùng</th>
                                        <th>Ngày hết hạn</th>

                                        <th>Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $count = 0;
                                    foreach ($data['voucherList'] as $key => $value) {
                                        ?>
                                        <tr>
                                            <td><?= ++$count ?></td>
                                            <td><?= $value['code'] ?></td>
                                            <td>-<?= $value['percentDiscount'] ?>%</td>
                                            <td><?= $value['quantity'] ?></td>
                                            <td><?= $value['usedCount'] ?></td>
                                            <td><?= date("d/m/Y", strtotime($value['expirationDate'])) ?></td>

                                            <td>
                                                <a class="button-red"
                                                    href="<?= URL_ROOT . '/voucherManage/delete/' . $value['id'] ?>">Xóa</a>
                                                <a class="button-normal"
                                                    href="<?= URL_ROOT . '/voucherManage/edit/' . $value['id'] ?>">Sửa</a>
                                            </td>
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