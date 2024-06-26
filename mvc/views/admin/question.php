<?php require APP_ROOT . '/views/admin/inc/head.php'; ?>

<body>
    <?php require APP_ROOT . '/views/admin/inc/sidebar.php'; ?>

    <div class="main-content">

        <main>
            <section class="recent">
                <div class="activity-grid">
                    <div class="activity-card">
                        <h3>Danh sách hỏi đáp</h3>
                        <div class="table-responsive">
                            <table>
                                <thead>
                                    <tr>
                                        <th>STT</th>
                                        <th>Tên sản phẩm</th>
                                        <th>Tên khách hàng</th>
                                        <th>nội dung</th>
                                        <th>ngày</th>
                                        <th>Phản hồi</th>
                                        <th>Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $count = 0;
                                    foreach ($data['questionList'] as $key => $value) {
                                        ?>
                                        <tr>
                                            <td><?= ++$count ?></td>
                                            <td><?= $value['productName'] ?></td>
                                            <td><?= $value['fullName'] ?></td>
                                            <td><?= $value['content'] ?></td>
                                            <td><?= date("d/m/Y", strtotime($value['createdDate'])) ?></td>
                                            <?php
                                            if ($value['reply']) { ?>
                                                <td><span class="active"><?= $value['reply'] ?></span></td>
                                            <?php } else { ?>
                                                <td><span class="block">Chưa có phản hồi</span></td>
                                            <?php }
                                            ?>
                                            <td>
                                                <?php
                                                if ($value['reply']) { ?>
                                                    <div class="div_action">
                                                        <a class="button-normal"
                                                            href="<?= URL_ROOT . '/questionManage/reply/' . $value['id'] ?>">
                                                            Sửa
                                                        </a>

                                                        <a class="button-red"
                                                            href="<?= URL_ROOT . '/questionManage/delete/' . $value['id'] ?>">Xóa
                                                        </a>
                                                    </div>
                                                <?php } else { ?>
                                                    <a class="button-normal"
                                                        href="<?= URL_ROOT . '/questionManage/reply/' . $value['id'] ?>">Phản
                                                        hồi</a>
                                                <?php }
                                                ?>
                                            </td>
                                        </tr>
                                    <?php }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                        <div class="pagination">
                            <a
                                href="<?= URL_ROOT ?>/questionManage?page=<?= (isset($_GET['page'])) ? (($_GET['page'] <= 1) ? 1 : $_GET['page'] - 1) : 1 ?>">&laquo;</a>
                            <?php
                            for ($i = 1; $i <= $data['countPaging']; $i++) {
                                if (isset($_GET['page'])) {
                                    if ($i == $_GET['page']) { ?>
                                        <a class="active" href="<?= URL_ROOT ?>/questionManage?page=<?= $i ?>"><?= $i ?></a>
                                    <?php } else { ?>
                                        <a href="<?= URL_ROOT ?>/questionManage?page=<?= $i ?>"><?= $i ?></a>
                                    <?php }
                                } else {
                                    if ($i == 1) { ?>
                                        <a class="active" href="<?= URL_ROOT ?>/questionManage?page=<?= $i ?>"><?= $i ?></a>
                                    <?php } else { ?>
                                        <a href="<?= URL_ROOT ?>/questionManage?page=<?= $i ?>"><?= $i ?></a>
                                    <?php } ?>
                                <?php } ?>
                            <?php }
                            ?>
                            <a
                                href="<?= URL_ROOT ?>/questionManage?page=<?= (isset($_GET['page'])) ? ($_GET['page'] == $data['countPaging'] ? $_GET['page'] : $_GET['page'] + 1) : 2 ?>">&raquo;</a>
                        </div>
                    </div>
                </div>
            </section>

        </main>

    </div>
</body>

</html>