<?php require APP_ROOT . '/views/admin/inc/head.php'; ?>

<body>
    <?php require APP_ROOT . '/views/admin/inc/sidebar.php'; ?>

    <div class="main-content">
        <main>
            <section class="recent">
                <div class="activity-grid">
                    <div class="activity-card">
                        <h3>Cập nhật sản phẩm</h3>
                        <div class="form">
                            <form action="<?= URL_ROOT . '/productManage/edit' ?>" method="POST"
                                enctype="multipart/form-data">
                                <input type="hidden" name="id" value="<?= $data['product']['id'] ?>">
                                <p class="<?= $data['cssClass'] ?>">
                                    <?= isset($data['message']) ? $data['message'] : "" ?>
                                </p>
                                <label for="name">Tên sản phẩm</label>
                                <input type="text" id="name" name="name" required
                                    value="<?= $data['product']['name'] ?>">
                                <label for="cate">Danh mục</label>
                                <select name="cateId" id="cate">
                                    <?php
                                    foreach ($data['categoryList'] as $key => $value) { ?>
                                        <?php
                                        if ($value['id'] == $data['product']['cateId']) { ?>
                                            <option selected value="<?= $value['id'] ?>"><?= $value['name'] ?></option>
                                        <?php } else { ?>
                                            <option value="<?= $value['id'] ?>"><?= $value['name'] ?></option>
                                        <?php }
                                        ?>
                                    <?php }
                                    ?>
                                </select>



                                <label for="image">Hình ảnh 1</label>
                                <p>
                                    <img style="height: 300px;"
                                        src="<?= URL_ROOT . '/public/images/' . $data['product']['image'] ?>" alt="">
                                </p>
                                <label for="image">Hình ảnh 2</label>
                                <p>
                                    <img style="height: 300px;"
                                        src="<?= URL_ROOT . '/public/images/' . $data['product']['image2'] ?>" alt="">
                                </p>
                                <label for="image">Hình ảnh 3</label>
                                <p>
                                    <img style="height: 300px;"
                                        src="<?= URL_ROOT . '/public/images/' . $data['product']['image3'] ?>" alt="">
                                </p>
                                <label for="image">Chọn hình ảnh mới 1</label>
                                <input type="file" id="image" name="image">
                                <label for="image">Chọn hình ảnh mới 2</label>
                                <input type="file" id="image2" name="image2">
                                <label for="image">Chọn hình ảnh mới 3</label>
                                <input type="file" id="image3" name="image3">
                                <label for="originalPrice">Giá gốc</label>
                                <input type="number" id="originalPrice" name="originalPrice" required
                                    value="<?= $data['product']['originalPrice'] ?>">
                                <label for="promotionPrice">Giá khuyến mãi</label>
                                <input type="number" id="promotionPrice" name="promotionPrice" required
                                    onchange="check(this)" value="<?= $data['product']['promotionPrice'] ?>">
                                <label for="qty">Số lượng</label>
                                <input type="number" id="qty" name="qty" required
                                    value="<?= $data['product']['qty'] ?>">
                                <label for="weight">Trọng lượng (g):</label>
                                <input type="number" id="weight" name="weight" required
                                    value="<?= $data['product']['weight'] ?>">
                                <label for="des">Mô tả</label>
                                <textarea name="des" id="des" cols="30"
                                    rows="10"><?= $data['product']['des'] ?></textarea>
                                <input type="submit" value="Lưu">
                                <a href="<?= URL_ROOT . '/productManage' ?>" class="back">Trở về</a>
                            </form>
                        </div>
                    </div>
                </div>
            </section>
        </main>

    </div>
    <script language='javascript' type='text/javascript'>
        function check(input) {
            input.setCustomValidity('');
            if (input.value > document.getElementById('originalPrice').value) {
                input.setCustomValidity('Giá khuyến mãi không được lớn hơn giá gốc!');
            } else {
                input.setCustomValidity('');
            }
        }
    </script>
</body>

</html>