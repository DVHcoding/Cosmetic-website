<div class="pagination">
    <a href="<?= URL_ROOT ?>/product/category/<?= $data['CateId'] ?>?page=<?= (isset($_GET['page'])) ? (($_GET['page'] <= 1) ? 1 : $_GET['page'] - 1) : 1 ?>">&laquo;</a>
    <?php
    for ($i = 1; $i <= $data['countPaging']; $i++) {
        if (isset($_GET['page'])) {
            if ($i == $_GET['page']) { ?>
                <a class="active" href="<?= URL_ROOT ?>/product/category/<?= $data['CateId'] ?>?page=<?= $i ?>"><?= $i ?></a>
            <?php } else { ?>
                <a href="<?= URL_ROOT ?>/product/category/<?= $data['CateId'] ?>?page=<?= $i ?>"><?= $i ?></a>
            <?php  }
        } else {
            if ($i == 1) { ?>
                <a class="active" href="<?= URL_ROOT ?>/product/category/<?= $data['CateId'] ?>?page=<?= $i ?>"><?= $i ?></a>
            <?php  } else { ?>
                <a href="<?= URL_ROOT ?>/product/category/<?= $data['CateId'] ?>?page=<?= $i ?>"><?= $i ?></a>
            <?php   } ?>
        <?php  } ?>
    <?php }
    ?>
    <a href="<?= URL_ROOT ?>/product/category/<?= $data['CateId'] ?>?page=<?= (isset($_GET['page'])) ? ($_GET['page'] == $data['countPaging'] ? $_GET['page'] : $_GET['page'] + 1) : 2 ?>">&raquo;</a>
</div>