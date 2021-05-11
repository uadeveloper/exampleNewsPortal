<?php include __DIR__ . "/../../global/header.php"; ?>
<?php include __DIR__ . "/../../global/site_sidebar.php"; ?>
    <main>
        <div class="container py-2">

            <div class="d-flex">
                <div class="p-2 w-100"></div>
                <div class="p-2">
                    <a href="#" onclick="adminUsers.modal(0); return false;" type="button" class="btn btn-primary">
                        Добавить
                    </a>
                </div>
            </div>
            <table class="table table-striped">
                <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Логин</th>
                    <th scope="col">Права</th>
                    <th scope="col">Дата регистрации</th>
                    <th scope="col"></th>
                </tr>
                </thead>
                <tbody>
                <?php
                if (!$users) {
                    ?>
                    <tr>
                        <td colspan="5" class="text-center">Пока пользователей нет. Как вы суда попали?</td>
                    </tr>
                    <?php
                }
                ?>
                <?php
                foreach ($users as $userItem) {
                    ?>
                    <tr>
                        <th scope="row"><?php echo  $userItem->getId() ?></th>
                        <td>
                            <a href="#" onclick="adminUsers.modal(<?php echo  $userItem->getId() ?>); return false;">
                                <?php echo  $userItem->getLogin() ?>
                            </a>
                        </td>
                        <td>
                                <?php foreach($userItem->getUserPermissions() AS $permissionItem) { ?>
                                    <span class="badge bg-success"><?php echo  $permissionItem->getPermission() ?></span>
                                <?php } ?>
                        </td>
                        <td><?php echo  $userItem->getCreated() ?></td>
                        <td class="text-end">
                            <div class="btn-group" role="group" aria-label="Basic mixed styles example">
                                <a href="#" onclick="adminUsers.modal(<?php echo  $userItem->getId() ?>); return false;" type="button" class="btn btn-outline-primary btn-sm">Редактировать</a>
                                <a href="#" onclick="adminUsers.delete(<?php echo  $userItem->getId() ?>); return false;" type="button" class="btn btn-outline-danger btn-sm">Удалить</a>
                            </div>
                        </td>
                    </tr>
                    <?php
                }
                ?>
                </tbody>
            </table>

            <?php
            $countNext = $usersLimits['count'] - ($usersLimits['offset'] + $usersLimits['limit']);
            if($countNext > 10) {
                $countNext = 10;
            }
            ?>
                <div class="py-2">

                    <?php if($usersLimits['offset']) { ?>
                        <a href="/admin/users/" class="btn btn-sm btn-outline-secondary">В начало</a>
                    <?php } ?>

                    <?php if($countNext > 0) { ?>
                        <a href="/admin/users/page-<?php echo ($usersLimits['offset'] + $usersLimits['limit'])?>/" class="btn btn-sm btn-outline-secondary">Следующие <?php echo $countNext?></a>
                    <?php } ?>
                </div>

        </div>
    </main>
<?php include __DIR__ . "/../../global/footer.php"; ?>