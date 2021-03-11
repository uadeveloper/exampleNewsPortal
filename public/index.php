<?php

require __DIR__ . '/../vendor/autoload.php';

$container = new \SiteCore\Components\Container\Container();

$container->set("db", function () {
    $pdo = new \PDO("mysql:host=localhost;dbname=newsPortal", "root", "root");
    return $pdo;
});

$container->set("view", function () {
    return new \SiteCore\Components\Template\View(__DIR__ . "/../templates/");
});

$container->set("session", function () {
    return new \SiteCore\Components\Session\Session();
});

$container->set("route", function () {

    $route = new \SiteCore\Components\Routing\Route();

    # Logout
    $route->get("/404.html", \App\Controller\AuthController::class, "error404");

    # News pages
    $route->get("/", \App\Controller\NewsController::class, "index");
    $route->get("/news/page-([0-9]*)/", \App\Controller\NewsController::class, "newsListPage");
    $route->get("/news/([0-9]*)/", \App\Controller\NewsController::class, "oneNews");

    # Login page
    $route->get("/auth/login/", \App\Controller\AuthController::class, "loginPage");
    $route->post("/auth/login/", \App\Controller\AuthController::class, "login");

    # Register page
    $route->get("/auth/register/", \App\Controller\AuthController::class, "registerPage");
    $route->post("/auth/register/", \App\Controller\AuthController::class, "register");

    # Logout
    $route->get("/auth/logout/", \App\Controller\AuthController::class, "logout");

    # Admin news pages && ajax
    $route->get("/admin/news/", \App\Controller\AdminNewsController::class, "index");
    $route->get("/admin/news/page-([0-9]*)/", \App\Controller\AdminNewsController::class, "index");
    $route->delete("/admin/news/([0-9]*)/", \App\Controller\AdminNewsController::class, "delete");
    $route->get("/admin/news/([0-9]*)/", \App\Controller\AdminNewsController::class, "view");
    $route->post("/admin/news/", \App\Controller\AdminNewsController::class, "store");

    # Admin users pages && ajax
    $route->get("/admin/users/", \App\Controller\AdminUsersController::class, "index");
    $route->get("/admin/users/page-([0-9]*)/", \App\Controller\AdminUsersController::class, "index");
    $route->get("/admin/users/([0-9]*)/", \App\Controller\AdminUsersController::class, "view");
    $route->post("/admin/users/", \App\Controller\AdminUsersController::class, "store");
    $route->delete("/admin/users/([0-9]*)/", \App\Controller\AdminUsersController::class, "delete");

    return $route;

});

$app = new \SiteCore\App($container);
$app->run($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);