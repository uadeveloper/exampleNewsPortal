<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserPermissionRepository;
use App\Repository\UserRepository;
use SiteCore\Components\Routing\Route;

class AdminUsersController extends AdminBaseController
{

    private $userRepository;
    private $permissionsRepository;

    public function index(Route $route) : void
    {
        $this->userRepository = new UserRepository($this->db);

        $usersLimits = [
            "offset" => (int)$route->getRouteItem()->getParams()[0],
            "limit" => 10,
            "count" => 0
        ];

        $users = $this->userRepository->loadUsers($usersLimits);

        $this->view->assign("title", "Администратор - Управление пользователями.");
        $this->view->render("/admin/users/index.php", [
            "users" => $users,
            "usersLimits" => $usersLimits,
        ]);

    }

    public function store() : void
    {
        $this->userRepository = new UserRepository($this->db);
        $this->permissionsRepository = new UserPermissionRepository($this->db);

        $id = (int)$_POST["id"];
        $login = trim(htmlspecialchars($_POST['login']));
        $password = trim($_POST['password']);

        if ($id) {
            $userItem = $this->userRepository->findByColumn("id", $id);
            if (!$userItem) {
                echo json_encode([
                    "result" => false,
                    "error" => "Пользователь не найден."
                ]);
                return;
            }
        } else {
            $userItem = new User();
        }

        # Проверка логина
        if (mb_strlen($login) < 6) {
            echo(json_encode([
                "result" => false,
                "error" => "Логин должен содержать минимум 6 сиволов."
            ]));
            return;
        }

        $userExistLogin = $this->userRepository->findByColumn("login", $login);
        if ($userExistLogin && $userExistLogin->getId() != $userItem->getId()) {
            echo(json_encode([
                "result" => false,
                "error" => "Такой логин уже занят."
            ]));
            return;
        } else {
            $userItem->setLogin($login);
        }

        if ($password) {

            if (mb_strlen($password) < 6 || !preg_match("#[0-9]+#", $password) || !preg_match("#[a-zA-ZА-Яа-я]+#", $password)) {
                echo(json_encode([
                    "result" => false,
                    "error" => "Пароль должен быть >= 6 символов и содержать букву и цифру."
                ]));
                return;
            }

            $userItem->setPasswordHash(password_hash($password, PASSWORD_DEFAULT));

        }

        $userPermissions = [];
        if (is_array($_POST['permissions'])) {
            foreach ($_POST['permissions'] as $permissionId) {
                $userPermissions[] = $this->permissionsRepository->findById($permissionId);
            }
        }
        $userItem->setUserPermissions($userPermissions);

        $this->userRepository->save($userItem);
        $this->permissionsRepository->saveUserPermissionsRels($userItem);

        echo json_encode([
            "result" => true,
        ]);
    }

    public function view(Route $route) : void
    {
        $this->userRepository = new UserRepository($this->db);
        $this->permissionsRepository = new UserPermissionRepository($this->db);

        $id = (int)$route->getRouteItem()->getParams()[0];

        if ($id) {
            $userItem = $this->userRepository->findByColumn("id", $id);
        } else {
            $userItem = new User();
        }

        $permissions = $this->permissionsRepository->loadAllUsersPermissions();

        echo json_encode([
            "result" => true,
            "content" => $this->view->fetch("admin/users/form.php", [
                "userItem" => $userItem,
                "permissions" => $permissions,
            ])
        ]);

    }

    public function delete(Route $route) : void
    {
        $this->userRepository = new UserRepository($this->db);

        $id = (int)$route->getRouteItem()->getParams()[0];

        $userItem = $this->userRepository->findByColumn("id", $id);
        if (!$userItem) {
            echo json_encode([
                "result" => false,
                "error" => "Пользователь не найден."
            ]);
            return;
        }

        $this->userRepository->delete($userItem);

        echo json_encode([
            "result" => true
        ]);

    }

}