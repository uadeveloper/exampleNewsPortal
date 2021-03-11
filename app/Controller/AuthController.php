<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use SiteCore\AbstractController;
use SiteCore\Components\Routing\Route;
use SiteCore\Components\Session\Session;
use SiteCore\Components\Template\View;

class AuthController extends AbstractController
{
    private $userRepository;
    private $session;
    private $route;

    public function __construct(Session $session, \PDO $db, Route $route)
    {
        $this->session = $session;
        $this->route = $route;
        $this->userRepository = new UserRepository($db);
    }

    public function logout()
    {
        $this->session->delete("user_id");
        $this->route->redirect("/");
    }

    public function loginPage(View $view)
    {
        if ($this->session->has("user_id")) {
            $this->route->redirect("/");
        }
        $view->render("auth/loginPage.php", [
            "title" => "Страница авторизации"
        ]);
    }

    public function login()
    {
        $login = trim($_POST['login']);
        $password = trim($_POST['password']);

        $userEntity = $this->userRepository->findByColumn("login", $login);

        if (!$userEntity || !$password || !password_verify($password, $userEntity->getPasswordHash())) {
            echo(json_encode([
                "error" => "Неверный логин или пароль."
            ]));
            return;
        }

        $this->session->set("user_id", $userEntity->getId());

        echo(json_encode([
            "result" => true
        ]));
    }

    public function registerPage(View $view)
    {
        if ($this->session->has("user_id")) {
            $this->route->redirect("/");
        }

        $view->render("auth/registerPage.php", [
            "title" => "Страница регистрации"
        ]);

    }

    public function register()
    {

        $login = trim($_POST['login']);
        $password = trim($_POST['password']);

        if (mb_strlen($login) < 6) {
            echo(json_encode([
                "result" => false,
                "error" => "Логин должен содержать минимум 6 сиволов."
            ]));
            return;
        }

        if (mb_strlen($password) < 6 || !preg_match("#[0-9]+#", $password) || !preg_match("#[a-zA-ZА-Яа-я]+#", $password)) {
            echo(json_encode([
                "result" => false,
                "error" => "Пароль должен быть >= 6 символов и содержать букву и цифру."
            ]));
            return;
        }

        $userEntity = $this->userRepository->findByColumn("login", $login);
        if ($userEntity) {
            echo(json_encode([
                "result" => false,
                "error" => "Такой логин уже занят."
            ]));
            return;
        }

        $userEntity = new User();
        $userEntity->setLogin(htmlspecialchars($login));
        $userEntity->setPasswordHash(password_hash($password, PASSWORD_DEFAULT));

        $this->userRepository->save($userEntity);

        $this->session->set("user_id", $userEntity->getId());

        echo(json_encode([
            "result" => true
        ]));

    }

    public function error404(View $view) {
        $view->render("errors/404.php", [
            "title" => "Ничего не нашли"
        ]);
    }

}