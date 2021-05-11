<?php

namespace App\Controller;

use App\Entity\News;
use App\Repository\NewsRepository;
use SiteCore\Components\Routing\Route;

class AdminNewsController extends AdminBaseController
{

    private $newsRepository;

    public function index(Route $route)
    {

        $this->newsRepository = new NewsRepository($this->db);

        $newsLimits = [
            "offset" => (int)$route->getRouteItem()->getParams()[0],
            "limit" => 10,
            "count" => 0
        ];

        $news = $this->newsRepository->loadNews($newsLimits);

        $this->view->assign("title", "Администратор - Управление новостями.");
        $this->view->render("/admin/news/index.php", [
            "news" => $news,
            "newsLimits" => $newsLimits,
        ]);
    }

    public function view(Route $route)
    {

        $this->newsRepository = new NewsRepository($this->db);

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

        $this->newsRepository = new NewsRepository($this->db);

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

        if (\mb_strlen(htmlspecialchars($_POST['content'])) > 256) {
            echo json_encode([
                "result" => false,
                "error" => "Максимальная длина заголовка 500 символов."
            ]);
            return;
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
        $this->newsRepository = new NewsRepository($this->db);

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