<div class="modal" id="modalUser" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Пользователь</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="/admin/users/" method="post" class="coreAppAjaxForm" data-success-url="/admin/users/">

                    <?php if($userItem->getId()) { ?>
                            <input type="hidden" name="id" value="<?=$userItem->getId()?>">
                    <?php } ?>

                    <div class="mb-3">
                        <label for="inputName" class="form-label">Логин пользователя</label>
                        <input type="text" name="login" class="form-control" id="inputName" value="<?=$userItem->getLogin()?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="inputPassword" class="form-label">Новый пароль</label>
                        <input type="text" name="password" class="form-control" id="inputPassword" value="">
                        <div id="passwordHelp" class="form-text">Оставьте пустым, если не хотите изменить.</div>
                    </div>

                    <div class="b-3">
                        <label for="inputDate" class="form-label">Права пользователя</label>


                        <?php foreach($permissions AS $permissionItem) { ?>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="permissions[]" value="<?= $permissionItem->getId() ?>" <?php if($userItem->hasUserPermission($permissionItem->getPermission())) { ?> checked <?php } ?>id="permissionCheckbox<?= $permissionItem->getId() ?>">
                                <label class="form-check-label" for="permissionCheckbox<?= $permissionItem->getId() ?>">
                                    <?= $permissionItem->getPermission() ?>
                                </label>
                            </div>
                        <?php } ?>
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