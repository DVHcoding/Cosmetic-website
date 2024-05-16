<?php require APP_ROOT . '/views/admin/inc/head.php'; ?>

<body>
    <?php require APP_ROOT . '/views/admin/inc/sidebar.php'; ?>

    <div class="main-content">


        <main>
            <section class="recent">
                <div class="activity-grid">
                    <div class="activity-card">
                        <h3>Phản hồi</h3>
                        <div class="form">
                            <form action="<?= URL_ROOT . '/ratingManage/reply'?>" method="POST">
                            <input type="hidden" name="id" value="<?= $data['rating']['id'] ?>">
                                <p class="<?= $data['cssClass'] ?>"><?= isset($data['message']) ? $data['message'] : "" ?></p>
                                <label for="name">Tên sản phẩm</label>
                                <input type="text" id="name" required value="<?= $data['rating']['productName'] ?>" disabled>
                                <label for="image">Hình ảnh</label><br>
                                <img src="<?= URL_ROOT ?>/public/images/<?= $data['rating']['productImage'] ?>" alt="" srcset=""><br>
                                <label for="star">Số sao</label><br>
                                <?php
                                for ($i = 0; $i < $data['rating']['star']; $i++) { ?>
                                    <span class="ti-star" style="color: orange;"></span>
                                <?php } echo '('.$data['rating']['star'].'/5)';?><br>
                                <label for="content">Nội dung</label>
                                <input type="text" id="content" required value="<?= $data['rating']['content'] ?>" disabled>
                                <label for="reply">Phản hồi</label><br>
                                <textarea type="text" id="reply" required name="reply"><?= isset($data['rating']['reply']) ? $data['rating']['reply'] : "" ?></textarea>
                                <input type="submit" value="Lưu">
                                <a href="<?= URL_ROOT . '/ratingManage' ?>" class="back">Trở về</a>
                            </form>
                        </div>
                    </div>
                </div>
            </section>

        </main>

    </div>
</body>

</html>