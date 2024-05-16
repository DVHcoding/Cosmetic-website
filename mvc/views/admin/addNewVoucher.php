<?php require APP_ROOT . '/views/admin/inc/head.php'; ?>

<body>
    <?php require APP_ROOT . '/views/admin/inc/sidebar.php'; ?>

    <div class="main-content">
        <main>
            <section class="recent">
                <div class="activity-grid">
                    <div class="activity-card">
                        <h3>Thêm mới Voucher</h3>
                        <div class="form">
                            <form action="<?= URL_ROOT . '/voucherManage/add' ?>" method="POST">
                                <p class="<?= $data['cssClass'] ?>"><?= isset($data['message']) ? $data['message'] : "" ?></p>
                                <label for="code">Code</label>
                                <input type="text" name="code" required minlength="5" maxlength="10">
                                <label for="percentDiscount">Phần trăm giảm (%)</label>
                                <input type="number" name="percentDiscount" required min="0" max="99">
                                <label for="quantity">Số lượng</label>
                                <input type="number" name="quantity" required min="0">
                                <label for="expirationDate">Ngày hết hạn</label>
                                <input type="date" name="expirationDate" required>
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