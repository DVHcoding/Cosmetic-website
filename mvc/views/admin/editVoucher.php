<?php require APP_ROOT . '/views/admin/inc/head.php'; ?>

<body>
    <?php require APP_ROOT . '/views/admin/inc/sidebar.php'; ?>

    <div class="main-content">

        <main>
            <section class="recent">
                <div class="activity-grid">
                    <div class="activity-card">
                        <h3>Sửa voucher</h3>
                        <div class="form">
                            <form action="<?= URL_ROOT . '/voucherManage/edit/' . $data['voucher']['id'] ?>"
                                method="POST">

                                <input type="hidden" name="id" value="<?= $data['voucher']['id'] ?>">

                                <p class="<?= $data['cssClass'] ?>">
                                    <?= isset($data['message']) ? $data['message'] : "" ?>
                                </p>

                                <label for="name">Tên voucher</label>

                                <input type="text" id="name" name="name" required
                                    value="<?= $data['voucher']['code'] ?>">

                                <input type="submit" value="Lưu">

                                <a href="<?= URL_ROOT . '/voucherManage' ?>" class="back">Trở về</a>
                            </form>
                        </div>
                    </div>
                </div>
            </section>

        </main>

    </div>
</body>

</html>