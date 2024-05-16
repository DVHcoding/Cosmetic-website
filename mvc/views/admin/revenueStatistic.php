<?php require APP_ROOT . '/views/admin/inc/head.php'; ?>

<body>
    <?php require APP_ROOT . '/views/admin/inc/sidebar.php'; ?>

    <div class="main-content">

        <main>
            <section class="recent">
                <div class="activity-grid">
                    <div class="activity-card">
                        <h3>Thống kê doanh thu</h3>
                        <div class="form">
                            <form action="<?= URL_ROOT . '/statisticManage/revenue' ?>" method="GET">
                                <label for="from">Từ</label>
                                <input type="date" name="from" id="from" value="<?= (isset($data['from']) ? $data['from'] : "") ?>" required>
                                <label for="to">Đến</label>
                                <input type="date" name="to" id="to" value="<?= (isset($data['to']) ? $data['to'] : "") ?>" required>
                                <input type="submit" value="Hiển thị">
                            </form>
                        </div> <br> <br>
                        <?php
                        if (isset($data['revenueList'])) { ?>
                            <div class="table-responsive">
                                <table>
                                    <thead>
                                        <tr>
                                            <th>STT</th>
                                            <th>Doanh thu</th>
                                            <th>Ngày</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $count = 0;
                                        foreach ($data['revenueList'] as $key => $value) {
                                        ?>
                                            <tr>
                                                <td><?= ++$count ?></td>
                                                <td><?= number_format($value['total']) ?>đ</td>
                                                <td><?= $value['day'] ?></td>
                                            </tr>
                                        <?php }
                                        ?>
                                    </tbody>
                                </table>
                                <?php
                                if (count($data['revenueList']) > 0) { ?>
                                    <a href="<?= URL_ROOT . '/statisticManage/revenueToExcel/' . $data['from'] . '/' . $data['to'] ?>" class="button right">Xuất EXCEL</a>
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