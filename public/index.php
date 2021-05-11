<?php

require __DIR__ . '/../core/Psr4Autoloader.php';
$autoloader = new \SiteCore\Psr4Autoloader();
$autoloader->addNamespace("SiteCore", __DIR__ . "/../core");
$autoloader->addNamespace("App", __DIR__ . "/../app");
$autoloader->register();

use App\Controller\AdminNewsController;
use App\Controller\AdminUsersController;
use App\Controller\AuthController;
use App\Controller\NewsController;
use SiteCore\App;
use SiteCore\Components\Container\Container;
use SiteCore\Components\Routing\Route;
use SiteCore\Components\Session\Session;
use SiteCore\Components\Template\View;

$container = new Container();

$container->set("db", function () {
    return new PDO("mysql:host=localhost;dbname=newsPortal", "root", "root");
});

$container->set("view", function () {
    return new View(__DIR__ . "/../templates/");
});

$container->set("session", function () {
    return new Session();
});

$container->set("route", function () {

    $route = new Route();

    # Logout
    $route->get("/404.html", AuthController::class, "error404");

    # News pages
    $route->get("/", NewsController::class, "index");
    $route->get("/news/page-([0-9]*)/", NewsController::class, "newsListPage");
    $route->get("/news/([0-9]*)/", NewsController::class, "oneNews");

    # Login page
    $route->get("/auth/login/", AuthController::class, "loginPage");
    $route->post("/auth/login/", AuthController::class, "login");

    # Register page
    $route->get("/auth/register/", AuthController::class, "registerPage");
    $route->post("/auth/register/", AuthController::class, "register");

    # Logout
    $route->get("/auth/logout/", AuthController::class, "logout");

    # Admin news pages && ajax
    $route->get("/admin/news/", AdminNewsController::class, "index");
    $route->get("/admin/news/page-([0-9]*)/", AdminNewsController::class, "index");
    $route->delete("/admin/news/([0-9]*)/", AdminNewsController::class, "delete");
    $route->get("/admin/news/([0-9]*)/", AdminNewsController::class, "view");
    $route->post("/admin/news/", AdminNewsController::class, "store");

    # Admin users pages && ajax
    $route->get("/admin/users/", AdminUsersController::class, "index");
    $route->get("/admin/users/page-([0-9]*)/", AdminUsersController::class, "index");
    $route->get("/admin/users/([0-9]*)/", AdminUsersController::class, "view");
    $route->post("/admin/users/", AdminUsersController::class, "store");
    $route->delete("/admin/users/([0-9]*)/", AdminUsersController::class, "delete");

    return $route;

});

$app = new App($container);
$app->run($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);