<?php

namespace App\Controller;

use App\Entity\News;
use App\Repository\NewsRepository;
use App\Repository\UserRepository;
use SiteCore\AbstractController;
use SiteCore\Components\Routing\Route;
use SiteCore\Components\Session\Session;
use SiteCore\Components\Template\View;

class AdminNewsController extends AbstractController
{
    private $db;
    private $view;
    private $newsRepository;

    public function __construct(Session $session, View $view, \PDO $db, Route $route)
    {

        $this->db = $db;
        $this->view = $view;
        $this->newsRepository = new NewsRepository($db);

        $userRepository = new UserRepository($db);

        if ($session->has("user_id")) {
            $this->user = $userRepository->findById($session->get("user_id"));
            $view->assign("user", $this->user);
        }

        if (!$this->user || !$this->user->hasUserPermission("admin")) {
            $route->redirect("/");
        }

        $this->view->assign("title", "Администратор - Управление новостями.");

    }

    public function index(Route $route)
    {
        $newsLimits = [
            "offset" => (int)$route->getRouteItem()->getParams()[0],
            "limit" => 10,
            "count" => 0
        ];

        $news = $this->newsRepository->loadNews($newsLimits);

        $this->view->render("/admin/news/index.php", [
            "news" => $news,
            "newsLimits" => $newsLimits,
        ]);
    }

    public function view(Route $route)
    {
        $newsId = (int)$route->getRouteItem()->getParams()[0];

        if ($newsId) {
            $newsItem = $this->newsRepository->findByColumn("id", $newsId);
        } else {
            $newsItem = new News();
            $newsItem->setPublicationDate(date("Y-m-d H:i:s"));
        }

        echo json_encode([
            "result" => true,
            "content" => $this->view->fetch("admin/news/form.php", [
                "newsItem" => $newsItem,
            ])
        ]);
    }

    public function store()
    {
        $newsId = (int)$_POST["id"];

        if ($newsId) {
            $newsItem = $this->newsRepository->findByColumn("id", $newsId);
            if (!$newsItem) {
                echo json_encode([
                    "result" => false,
                    "error" => "Новость не найдена."
                ]);
                return;
            }
        } else {
            $newsItem = new News();
        }

        try {
            $publicationDate = new \DateTime($_POST['publication_date']);
        } catch (\Exception $ex) {
            echo json_encode([
                "result" => false,
                "error" => "Неверно указана дата публикации."
            ]);
            return;
        }

        $newsItem->setContent(htmlspecialchars($_POST['content']));
        $newsItem->setShortContent(htmlspecialchars($_POST['short_content']));
        $newsItem->setName(htmlspecialchars($_POST['name']));
        $newsItem->setPublicationDate($publicationDate->format("Y-m-d H:i:s"));

        $this->newsRepository->save($newsItem);

        echo json_encode([
            "result" => true,
        ]);

    }

    public function delete(Route $route)
    {
        $id = (int)$route->getRouteItem()->getParams()[0];

        $newsItem = $this->newsRepository->findByColumn("id", $id);
        if (!$newsItem) {
            echo json_encode([
                "result" => false,
                "error" => "Новость не найдена."
            ]);
            return;
        }

        $this->newsRepository->delete($newsItem);

        echo json_encode([
            "result" => true
        ]);

    }

}