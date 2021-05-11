<?php include __DIR__ . "/../../global/header.php"; ?>
<?php include __DIR__ . "/../../global/site_sidebar.php"; ?>
    <main>
        <div class="container py-2">

            <div class="d-flex">
                <div class="p-2 w-100"></div>
                <div class="p-2">
                    <a href="#" onclick="adminNews.modal(0); return false;" type="button" class="btn btn-primary">
                        Добавить
                    </a>
                </div>
            </div>
            <table class="table table-striped">
                <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Название</th>
                    <th scope="col">Краткое содержание</th>
                    <th scope="col">Дата публикации</th>
                    <th scope="col">

                    </th>
                </tr>
                </thead>
                <tbody>
                <?php
                if (!$news) {
                    ?>
                    <tr>
                        <td colspan="5" class="text-center">Пока новостей нет</td>
                    </tr>
                    <?php
                }
                ?>
                <?php
                foreach ($news as $newsEntity) {
                    ?>
                    <tr>
                        <th scope="row"><?php echo $newsEntity->getId() ?></th>
                        <td>
                            <a href="#" onclick="adminNews.modal(<?php echo $newsEntity->getId() ?>); return false;">
                                <?php echo $newsEntity->getName() ?>
                            </a>
                        </td>
                        <td><?php echo $newsEntity->getShortContent() ?></td>
                        <td><?php echo $newsEntity->getPublicationDate() ?></td>
                        <td class="text-end">
                            <div class="btn-group" role="group" aria-label="Basic mixed styles example">
                                <a href="#" onclick="adminNews.modal(<?php echo $newsEntity->getId() ?>); return false;" type="button" class="btn btn-outline-primary btn-sm">Редактировать</a>
                                <a href="#" onclick="adminNews.delete(<?php echo $newsEntity->getId() ?>); return false;" type="button" class="btn btn-outline-danger btn-sm">Удалить</a>
                            </div>
                        </td>
                    </tr>
                    <?php
                }
                ?>
                </tbody>
            </table>



            <?php
            $countNext = $newsLimits['count'] - ($newsLimits['offset'] + $newsLimits['limit']);
            if($countNext > 10) {
                $countNext = 10;
            }
            ?>
                <div class="py-2">

                    <?php if($newsLimits['offset']) { ?>
                        <a href="/admin/news/" class="btn btn-sm btn-outline-secondary">В начало</a>
                    <?php } ?>

                    <?php if($countNext > 0) { ?>
                        <a href="/admin/news/page-<?php echo ($newsLimits['offset'] + $newsLimits['limit'])?>/" class="btn btn-sm btn-outline-secondary">Следующие <?php echo $countNext; ?></a>
                    <?php } ?>
                </div>

        </div>
    </main>
<?php include __DIR__ . "/../../global/footer.php"; ?>