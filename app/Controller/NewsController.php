<?php

/**
 * Index and news pages
 */

namespace App\Controller;

use App\Repository\NewsRepository;
use App\Repository\UserRepository;
use PDO;
use SiteCore\AbstractController;
use SiteCore\Components\Routing\Route;
use SiteCore\Components\Session\Session;
use SiteCore\Components\Template\View;

class NewsController extends AbstractController
{
    private $view;
    private $newsRepository;

    public function __construct(Session $session, View $view, PDO $db)
    {
        $this->view = $view;
        $this->newsRepository = new NewsRepository($db);

        if ($session->has("user_id")) {
            $userRepository = new UserRepository($db);
            $userEntity = $userRepository->findById($session->get("user_id"));
            $view->assign("user", $userEntity);
        }

    }

    public function index() #: Response
    {

        $newsLimits = [
            "offset" => 0,
            "limit" => 10,
            "count" => 0
        ];

        $news = $this->newsRepository->loadLastNews($newsLimits);

        $this->view->render("news/index.php", [
            "news" => $news,
            "newsLimits" => $newsLimits,
            "title" => "Новости - главная"
        ]);
    }

    public function newsListPage(Route $route)
    {

        $newsLimits = [
            "offset" => $route->getRouteItem()->getParams()[0],
            "limit" => 10,
            "count" => 0
        ];

        $news = $this->newsRepository->loadLastNews($newsLimits);

        $this->view->render("news/news.php", [
            "news" => $news,
            "newsLimits" => $newsLimits,
            "title" => "Новости"
        ]);

    }

    public function oneNews(Route $route)
    {

        $newsItem = $news = $this->newsRepository->findByColumn("id", $route->getRouteItem()->getParams()[0]);

        if (!$newsItem) {
            $route->redirect("/");
        }

        $this->view->render("news/oneNews.php", [
            "newsItem" => $newsItem,
            "title" => "Новости - " . $newsItem->getName()
        ]);

    }


}