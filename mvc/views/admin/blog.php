<?php require APP_ROOT . '/views/admin/inc/head.php'; ?>

<body>
    <?php require APP_ROOT . '/views/admin/inc/sidebar.php'; ?>

    <div class="main-content">

        <header>
            <div class="search-wrapper">
                <form action="<?= URL_ROOT ?>/blogManage/index" method="get">
                    <input type="search" placeholder="Tìm kiếm" name="keyword">
                </form>
            </div>
        </header>

        <main>
            <section class="recent">
                <div class="activity-grid">
                    <div class="activity-card">
                        <a href="<?= URL_ROOT . '/blogManage/add' ?>" class="button right">Thêm mới</a>
                        <h3>Danh sách Blog</h3>
                        <div class="table-responsive">
                            <table>
                                <thead>
                                    <tr>
                                        <th>STT</th>
                                        <th>Tiêu đề</th>
                                        <th>Tác giả</th>
                                        <th>Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $count = 0;
                                    foreach ($data['blogList'] as $key => $value) {
                                        ?>
                                        <tr>
                                            <td><?= ++$count ?></td>
                                            <td><?= $value['title'] ?></td>
                                            <td><?= $value['author'] ?></td>
                                            <td>
                                                <a class="button-normal"
                                                    href="<?= URL_ROOT . '/blogManage/edit/' . $value['id'] ?>">Sửa</a>
                                                <a class="button-red"
                                                    href="<?= URL_ROOT . '/blogManage/delete/' . $value['id'] ?>">Xóa</a>
                                            </td>
                                        </tr>
                                    <?php }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                        <div class="pagination">
                            <a
                                href="<?= URL_ROOT ?>/blogManage?page=<?= (isset($_GET['page'])) ? (($_GET['page'] <= 1) ? 1 : $_GET['page'] - 1) : 1 ?>">&laquo;</a>
                            <?php
                            for ($i = 1; $i <= $data['countPaging']; $i++) {
                                if (isset($_GET['page'])) {
                                    if ($i == $_GET['page']) { ?>
                                        <a class="active" href="<?= URL_ROOT ?>/blogManage?page=<?= $i ?>"><?= $i ?></a>
                                    <?php } else { ?>
                                        <a href="<?= URL_ROOT ?>/blogManage?page=<?= $i ?>"><?= $i ?></a>
                                    <?php }
                                } else {
                                    if ($i == 1) { ?>
                                        <a class="active" href="<?= URL_ROOT ?>/blogManage?page=<?= $i ?>"><?= $i ?></a>
                                    <?php } else { ?>
                                        <a href="<?= URL_ROOT ?>/blogManage?page=<?= $i ?>"><?= $i ?></a>
                                    <?php } ?>
                                <?php } ?>
                            <?php }
                            ?>
                            <a
                                href="<?= URL_ROOT ?>/blogManage?page=<?= (isset($_GET['page'])) ? ($_GET['page'] == $data['countPaging'] ? $_GET['page'] : $_GET['page'] + 1) : 2 ?>">&raquo;</a>
                        </div>
                    </div>
                </div>
            </section>

        </main>

    </div>
</body>

</html>