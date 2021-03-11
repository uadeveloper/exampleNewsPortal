<div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-3">
    <?php
    foreach($news AS $newsEntity) {
        ?>
        <div class="col">
            <div class="card shadow-sm">
                <div class="card-body">
                    <p class="card-text"><?=$newsEntity->getShortContent()?></p>
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="btn-group">
                            <a href="/news/<?=$newsEntity->getId()?>/" class="btn btn-sm btn-outline-secondary">Читать</a>
                        </div>
                        <small class="text-muted"><?=$newsEntity->getPublicationDate()?></small>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
    ?>
</div>

<?php
$countNext = $newsLimits['count'] - ($newsLimits['offset'] + $newsLimits['limit']);
if($countNext > 10) {
    $countNext = 10;
}
?>
<div class="py-2">

    <?php if($newsLimits['offset']) { ?>
        <a href="/" class="btn btn-sm btn-outline-secondary">В начало</a>
    <?php } ?>

    <?php if($countNext > 0) { ?>
        <a href="/news/page-<?=($newsLimits['offset'] + $newsLimits['limit'])?>/" class="btn btn-sm btn-outline-secondary">Следующие <?=$countNext?></a>
    <?php } ?>
</div>
