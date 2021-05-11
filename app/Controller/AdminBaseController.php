<?php

namespace App\Controller;

use App\Repository\UserRepository;
use SiteCore\AbstractController;
use SiteCore\Components\Routing\Route;
use SiteCore\Components\Session\Session;
use SiteCore\Components\Template\View;

abstract class AdminBaseController extends AbstractController
{

    protected $db;
    protected $view;
    protected $user;

    public function __construct(Session $session, View $view, \PDO $db, Route $route)
    {

        $this->view = $view;
        $this->db = $db;

        $userRepository = new UserRepository($db);

        if ($session->has("user_id")) {
            $this->user = $userRepository->findById($session->get("user_id"));
            $view->assign("user", $this->user);
        }

        if (!$this->user || !$this->user->hasUserPermission("admin")) {
            $route->redirect("/");
        }

    }

}