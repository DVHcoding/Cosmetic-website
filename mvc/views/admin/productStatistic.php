<?php require APP_ROOT . '/views/admin/inc/head.php'; ?>

<body>
    <?php require APP_ROOT . '/views/admin/inc/sidebar.php'; ?>

    <div class="main-content">


        <main>
            <section class="recent">
                <div class="activity-grid">
                    <div class="activity-card">
                        <h3>Thống kê sản phẩm bán chạy</h3> <br>
                        <?php
                        if (isset($data['productList'])) { ?>
                            <div class="table-responsive">
                                <table>
                                    <thead>
                                        <tr>
                                            <th>STT</th>
                                            <th>Tên sản phẩm</th>
                                            <th>SL đã bán</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $count = 0;
                                        foreach ($data['productList'] as $key => $value) {
                                        ?>
                                            <tr>
                                                <td><?= ++$count ?></td>
                                                <td><?= $value['name'] ?></td>
                                                <td><?= $value['soldCount'] ?></td>
                                            </tr>
                                        <?php }
                                        ?>
                                    </tbody>
                                </table>
                                <?php
                                if (count($data['productList']) > 0) { ?>
                                    <a href="<?= URL_ROOT . '/statisticManage/revenueToExcel/' ?>" class="button right">Xuất EXCEL</a>
                                <?php  }
                                ?>
                            </div>
                        <?php }
                        ?>

                    </div>
                </div>
            </section>

        </main>

    </div>
</body>

</html>