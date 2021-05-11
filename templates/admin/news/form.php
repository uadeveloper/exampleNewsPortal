<div class="modal" id="modalNews" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Новость</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="/admin/news/" method="post" class="coreAppAjaxForm" data-success-url="/admin/news/">

                    <?php if($newsItem->getId()) { ?>
                            <input type="hidden" name="id" value="<?php echo $newsItem->getId()?>">
                    <?php } ?>

                    <div class="mb-3">
                        <label for="inputName" class="form-label">Название новости</label>
                        <input type="text" name="name" class="form-control" id="inputName" value="<?php echo $newsItem->getName()?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="textareaBlock" class="form-label">Краткое содержание</label>
                        <textarea name="short_content" class="form-control" id="textareaBlock" rows="3" required><?php echo $newsItem->getShortContent()?></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="textareaBlockFull" class="form-label">Полное содержание</label>
                        <textarea name="content" class="form-control" id="textareaBlockFull" rows="10"><?php echo $newsItem->getContent()?></textarea>
                    </div>
                    <div class="b-3">
                        <label for="inputDate" class="form-label">Дата публикации</label>
                        <input type="datetime-local" class="form-control" name="publication_date" id="inputDate-local" value="<?php echo date('Y-m-d\TH:i', strtotime($newsItem->getPublicationDate()))?>" required>
                    </div>

                    <div class="alert alert-danger formError" role="alert" style="display: none;"></div>

                </form>
            </div>
            <div class="modal-footer">
                <a href="#" type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отмена</a>
                <a href="#" onclick="$(this).parents('.modal').find('form').submit(); return false;" type="button" class="btn btn-primary">Сохранить</a>
            </div>
        </div>
    </div>
</div>