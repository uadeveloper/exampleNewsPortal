<?php include __DIR__ . "/../global/header.php"; ?>
<?php include __DIR__ . "/../global/site_sidebar.php"; ?>
    <main>
        <section class="py-5 container">
            <div class="row justify-content-md-center">
                <div class="col-5">
                    <form action="/auth/register/" method="post" class="coreAppAjaxForm" data-success-url="/">
                        <div class="card">
                            <div class="card-header">
                                Регистрация
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label for="inputLogin" class="form-label">Логин</label>
                                    <input name="login" type="login" class="form-control" id="inputLogin">
                                </div>
                                <div class="mb-3">
                                    <label for="inputPassword" class="form-label">Пароль</label>
                                    <input name="password" type="password" class="form-control" id="inputPassword">
                                </div>

                                <div class="alert alert-danger formError" role="alert" style="display: none;"></div>

                                <button type="submit" class="btn btn-primary">Зарегистрироваться</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </section>
    </main>
<?php include __DIR__ . "/../global/footer.php"; ?>