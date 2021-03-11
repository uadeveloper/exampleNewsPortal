<?php include __DIR__ . "/../global/header.php"; ?>
<?php include __DIR__ . "/../global/site_sidebar.php"; ?>
    <main>
        <div class="container py-5">
            <div class="card">
                <div class="card-header">
                    <?=$newsItem->getName()?>
                </div>
                <div class="card-body">
                    <p class="card-text"><?=$newsItem->getContent()?></p>
                    <a href="/" class="btn btn-outline-secondary btn-sm">На главную</a>
                </div>
            </div>
        </div>
    </main>
<?php include __DIR__ . "/../global/footer.php"; ?>