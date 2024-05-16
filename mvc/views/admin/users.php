<?php require APP_ROOT . '/views/admin/inc/head.php'; ?>

<body>
    <?php require APP_ROOT . '/views/admin/inc/sidebar.php'; ?>

    <div class="main-content">
        <header>
            <div class="search-wrapper">
                <form action="<?= URL_ROOT ?>/userManage/index" method="get">
                    <input type="search" placeholder="Tìm kiếm" name="keyword">
                </form>
            </div>
        </header>

        <main>
            <section class="recent">
                <div class="activity-grid">
                    <div class="activity-card">

                        <h3>Danh sách người dùng</h3>
                        <div class="table-responsive">
                            <table>
                                <thead>
                                    <tr>
                                        <th>STT</th>
                                        <th>Tên</th>
                                        <th>Email</th>
                                        <th>Địa chỉ</th>
                                        <th>Sđt</th>
                                        <th>Trạng thái</th>
                                        <th>Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $count = 0;
                                    foreach ($data['usersList'] as $key => $value) {
                                        ?>
                                        <tr>
                                            <td><?= ++$count ?></td>
                                            <td><?= $value['fullName'] ?></td>
                                            <td><?= $value['email'] ?></td>
                                            <td><?= $value['address'] ?></td>
                                            <td><?= $value['phone'] ?></td>
                                            <?php
                                            if ($value['status']) { ?>
                                                <td><span class="active">Kích hoạt</span></td>
                                            <?php } else { ?>
                                                <td><span class="block">Khóa</span></td>
                                            <?php }
                                            ?>
                                            <td>
                                                <?php
                                                if ($value['status']) { ?>
                                                    <a class="button-red"
                                                        href="<?= URL_ROOT . '/userManage/changeStatus/' . $value['id'] ?>">Khóa</a>
                                                <?php } else { ?>
                                                    <a class="button-green"
                                                        href="<?= URL_ROOT . '/userManage/changeStatus/' . $value['id'] ?>">Mở</a>
                                                <?php }
                                                ?>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php }
                                    ?>
                                </tbody>
                            </table>
                        </div>

                        <!-- Page -->
                        <div class="pagination">
                            <a
                                href="<?= URL_ROOT ?>/userManage?page=<?= (isset($_GET['page'])) ? (($_GET['page'] <= 1) ? 1 : $_GET['page'] - 1) : 1 ?>">&laquo;</a>
                            <?php
                            for ($i = 1; $i <= $data['countPaging']; $i++) {
                                if (isset($_GET['page'])) {
                                    if ($i == $_GET['page']) { ?>
                                        <a class="active" href="<?= URL_ROOT ?>/userManage?page=<?= $i ?>"><?= $i ?></a>
                                    <?php } else { ?>
                                        <a href="<?= URL_ROOT ?>/userManage?page=<?= $i ?>"><?= $i ?></a>
                                    <?php }
                                } else {
                                    if ($i == 1) { ?>
                                        <a class="active" href="<?= URL_ROOT ?>/userManage?page=<?= $i ?>"><?= $i ?></a>
                                    <?php } else { ?>
                                        <a href="<?= URL_ROOT ?>/userManage?page=<?= $i ?>"><?= $i ?></a>
                                    <?php } ?>
                                <?php } ?>
                            <?php }
                            ?>
                            <a
                                href="<?= URL_ROOT ?>/userManage?page=<?= (isset($_GET['page'])) ? ($_GET['page'] == $data['countPaging'] ? $_GET['page'] : $_GET['page'] + 1) : 2 ?>">&raquo;</a>
                        </div>
                    </div>
                </div>
            </section>

        </main>

    </div>
</body>

</html>