<?php require APP_ROOT . '/views/admin/inc/head.php'; ?>

<body>
    <?php require APP_ROOT . '/views/admin/inc/sidebar.php'; ?>

    <div class="main-content">
   

        <main>
            <section class="recent">
                <div class="activity-grid">
                    <div class="activity-card">
                        <h3>Thêm mới danh mục</h3>
                        <div class="form">
                            <form action="<?= URL_ROOT . '/categoryManage/add' ?>" method="POST">
                                <p class="<?= $data['cssClass'] ?>"><?= isset($data['message']) ? $data['message'] : "" ?></p>
                                <label for="name">Tên danh mục</label>
                                <input type="text" id="name" name="name" required>
                                <input type="submit" value="Lưu">
                                <a href="<?= URL_ROOT . '/categoryManage' ?>" class="back">Trở về</a>
                            </form>
                        </div>
                    </div>
                </div>
            </section>

        </main>

    </div>
</body>

</html>