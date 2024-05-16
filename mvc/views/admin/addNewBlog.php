<?php require APP_ROOT . '/views/admin/inc/head.php'; ?>
<script src="https://cdn.ckeditor.com/ckeditor5/34.0.0/classic/ckeditor.js"></script>

<body>
    <?php require APP_ROOT . '/views/admin/inc/sidebar.php'; ?>

    <div class="main-content">

        <main>
            <section class="recent">
                <div class="activity-grid">
                    <div class="activity-card">
                        <h3>Thêm mới Blog</h3>
                        <div class="form">
                            <form action="<?= URL_ROOT . '/blogManage/add' ?>" method="POST" enctype="multipart/form-data">
                                <p class="<?= $data['cssClass'] ?>"><?= isset($data['message']) ? $data['message'] : "" ?></p>
                                <label for="title">Tiều đề</label>
                                <input type="text" id="title" name="title" required>
                                <label for="image">Hình ảnh</label>
                                <input type="file" id="image" name="image" required>
                                <label for="editor">Nội dung</label>
                                <textarea name="content" id="editor">Viết nội dung tại đây...</textarea>
                                <script>
                                    ClassicEditor
                                        .create(document.querySelector('#editor'))
                                        .then(editor => {
                                            console.log(editor);
                                        })
                                        .catch(error => {
                                            console.error(error);
                                        });
                                </script>
                                <input type="submit" value="Lưu">
                                <a href="<?= URL_ROOT . '/blogManage' ?>" class="back">Trở về</a>
                            </form>
                        </div>
                    </div>
                </div>
            </section>

        </main>

    </div>
</body>

</html>