<?php

namespace App\Repository;

use App\Entity\News;
use SiteCore\AbstractEntity;
use SiteCore\PDORepository;

class NewsRepository extends PDORepository
{

    public function loadLastNews(&$limits = ['offset' => 0, 'limit' => 10, 'count' => null]): array
    {
        $sth = $this->db->prepare("SELECT SQL_CALC_FOUND_ROWS * FROM news WHERE publication_date <= NOW() ORDER BY publication_date DESC LIMIT :offset, :limit");
        $sth->bindParam(':offset', $limits['offset'], \PDO::PARAM_INT);
        $sth->bindParam(':limit', $limits['limit'], \PDO::PARAM_INT);
        $sth->execute();

        /**
         * SQL_CALC_FOUND_ROWS в mysql8 установлена как deprecated, нужно использовать 2 отдельных запроса.
         */
        $countQuery = $this->db->prepare('SELECT FOUND_ROWS() AS rows_count');
        $countQuery->execute();
        $limits['count'] = $countQuery->fetch(\PDO::FETCH_ASSOC)['rows_count'];

        $dbRaw = $sth->fetchAll(\PDO::FETCH_ASSOC);

        $items = [];
        foreach ($dbRaw as $dbRawItem) {
            $items[] = $this->bindEntity($dbRawItem);
        }

        return $items;
    }


    public function loadNews(&$limits = ['offset' => 0, 'limit' => 10, 'count' => null]): array
    {
        $sth = $this->db->prepare("SELECT SQL_CALC_FOUND_ROWS * FROM news ORDER BY id DESC LIMIT :offset, :limit");
        $sth->bindParam(':offset', $limits['offset'], \PDO::PARAM_INT);
        $sth->bindParam(':limit', $limits['limit'], \PDO::PARAM_INT);
        $sth->execute();

        $countQuery = $this->db->prepare('SELECT FOUND_ROWS() AS rows_count');
        $countQuery->execute();
        $limits['count'] = $countQuery->fetch(\PDO::FETCH_ASSOC)['rows_count'];

        $dbRaw = $sth->fetchAll(\PDO::FETCH_ASSOC);

        $news = [];
        foreach ($dbRaw as $dbRawItem) {
            $news[] = $this->bindEntity($dbRawItem);
        }

        return $news;
    }

    public function findByColumn(string $column, string $value): ?AbstractEntity
    {

        $column = preg_replace('/[^A-Za-z0-9_]+/', '', $column);
        $sth = $this->db->prepare("SELECT * FROM news WHERE {$column} = :value LIMIT 1");
        $sth->execute([
            ':value' => $value
        ]);
        $dbRaw = $sth->fetch(\PDO::FETCH_ASSOC);

        if ($dbRaw) {
            $entity = $this->bindEntity($dbRaw);
        }

        return $entity;

    }

    public function delete(News &$newsEntity)
    {
        if ($newsEntity->getId()) {
            $sth = $this->db->prepare("DELETE FROM news WHERE id = :id LIMIT 1");
            $sth->execute([
                ':id' => $newsEntity->getId()
            ]);
            unset($newsEntity);
        }
    }

    public function save(News &$newsEntity)
    {

        if ($newsEntity->getId() === null) {
            $sth = $this->db->prepare("INSERT INTO news (name, short_content, content, publication_date) VALUES (:name, :short_content, :content, :publication_date)");
            $sth->execute([
                'name' => $newsEntity->getName(),
                'short_content' => $newsEntity->getShortContent(),
                'content' => $newsEntity->getContent(),
                'publication_date' => $newsEntity->getPublicationDate()
            ]);
            # This block need use transaction :)
            $newsEntity->setId($this->db->lastInsertId());
        } else {
            $sth = $this->db->prepare("UPDATE news SET name = :name, short_content = :short_content, content = :content, publication_date = :publication_date WHERE id = :id");
            $sth->execute([
                ':id' => $newsEntity->getId(),
                'name' => $newsEntity->getName(),
                'short_content' => $newsEntity->getShortContent(),
                'content' => $newsEntity->getContent(),
                'publication_date' => $newsEntity->getPublicationDate()
            ]);
        }

    }

    private function bindEntity(array $data)
    {

        if (!$data) {
            throw new \Exception("Bind data entity is empty.");
        }

        $entity = new News();
        $entity->loadByArray($data);

        return $entity;

    }

}