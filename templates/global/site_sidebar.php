<header>
    <div class="navbar navbar-dark bg-dark shadow-sm">
        <div class="container">
            <a href="/" class="navbar-brand d-flex align-items-center">
                <strong>Новости</strong>
            </a>
            <div class="d-flex">
                <?php if($user && $user->getId()) { ?>
                    <div class="dropdown">
                        <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                            Привет, <?= $user->getLogin() ?>
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                            <?php if($user->hasUserPermission("admin")) { ?>
                                <li><a class="dropdown-item" href="/admin/news/">Управление новостями</a></li>
                                <li><a class="dropdown-item" href="/admin/users/">Управление пользователями</a></li>
                                <li><hr class="dropdown-divider"></li>
                            <?php } ?>
                            <li><a class="dropdown-item" href="/auth/logout/">Выйти</a></li>
                        </ul>
                    </div>
                <?php } else { ?>
                    <div class="dropdown">
                        <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                            Войти или зарегистрироваться
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                            <li><a class="dropdown-item" href="/auth/login/">Войти</a></li>
                            <li><a class="dropdown-item" href="/auth/register/">Зарегистрироваться</a></li>
                        </ul>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
</header>
