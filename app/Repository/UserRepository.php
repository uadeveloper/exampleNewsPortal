<?php

namespace App\Repository;

use App\Entity\User;
use PDO;
use SiteCore\AbstractEntity;
use SiteCore\PDORepository;

/**
 * Class UserRepository
 * @package App\Repository
 * TODO: перенести общие методы
 */
class UserRepository extends PDORepository
{

    public function loadUsers(&$limits = ['offset' => 0, 'limit' => 10, 'count' => null]): array
    {

        $userPermissionRepository = new UserPermissionRepository($this->db);

        $sth = $this->db->prepare("SELECT SQL_CALC_FOUND_ROWS * FROM users ORDER BY id DESC LIMIT :offset, :limit");
        $sth->bindParam(':offset', $limits['offset'], PDO::PARAM_INT);
        $sth->bindParam(':limit', $limits['limit'], PDO::PARAM_INT);
        $sth->execute();

        $countQuery = $this->db->prepare('SELECT FOUND_ROWS() AS rows_count');
        $countQuery->execute();
        $limits['count'] = $countQuery->fetch(PDO::FETCH_ASSOC)['rows_count'];

        $dbRaw = $sth->fetchAll(PDO::FETCH_ASSOC);

        $items = [];
        foreach ($dbRaw as $dbRawItem) {
            $userEntity = $this->bindEntity($dbRawItem);
            $userEntity->setUserPermissions($userPermissionRepository->loadUserPermissions($userEntity->getId()));
            $items[] = $userEntity;
        }

        return $items;
    }

    public function findById(int $id): ?AbstractEntity
    {

        $userPermissionRepository = new UserPermissionRepository($this->db);

        $sth = $this->db->prepare("SELECT * FROM users WHERE id = :id LIMIT 1");
        $sth->bindParam(':id', $id, PDO::PARAM_INT);
        $sth->execute();
        $dbUserRaw = $sth->fetch(PDO::FETCH_ASSOC);

        if (!$dbUserRaw) {
            return null;
        }

        $userEntity = $this->bindEntity($dbUserRaw);
        $userEntity->setUserPermissions($userPermissionRepository->loadUserPermissions($userEntity->getId()));

        return $userEntity;
    }

    public function findByColumn(string $column, string $value): ?AbstractEntity
    {

        $column = preg_replace('/[^A-Za-z0-9_]+/', '', $column);
        $sth = $this->db->prepare("SELECT * FROM users WHERE {$column} = :value LIMIT 1");
        $sth->bindParam(':value', $value, PDO::PARAM_STR);
        $sth->execute();
        $dbUserRaw = $sth->fetch(PDO::FETCH_ASSOC);

        if (!$dbUserRaw) {
            return null;
        }

        $userEntity = $this->bindEntity($dbUserRaw);

        $userPermissionRepository = new UserPermissionRepository($this->db);
        $userEntity->setUserPermissions($userPermissionRepository->loadUserPermissions($userEntity->getId()));

        return $userEntity;

    }

    public function save(User &$userEntity): void
    {

        if ($userEntity->getId() === null) {
            $sth = $this->db->prepare("INSERT INTO users (login, password_hash) VALUES (:login, :password_hash)");
            $sth->execute([
                'login' => htmlspecialchars($userEntity->getLogin()),
                'password_hash' => $userEntity->getPasswordHash()
            ]);
            # This block need use transaction :)
            $userEntity->setId($this->db->lastInsertId());
        } else {
            $sth = $this->db->prepare("UPDATE users SET login = :login, password_hash = :password_hash WHERE id = :id");
            $sth->execute([
                ':id' => $userEntity->getId(),
                'login' => htmlspecialchars($userEntity->getLogin()),
                'password_hash' => $userEntity->getPasswordHash()
            ]);
        }

    }

    public function delete(User &$userEntity): void
    {
        if ($userEntity->getId()) {
            $sth = $this->db->prepare("DELETE FROM users WHERE id = :id LIMIT 1");
            $sth->bindParam(':id', $userEntity->getId(), PDO::PARAM_INT);
            $sth->execute();
            unset($userEntity);
        }
    }

    private function bindEntity(array $data): User
    {

        if (!$data) {
            throw new \Exception("Bind data entity is empty.");
        }

        $userEntity = new User();
        $userEntity->loadByArray($data);

        return $userEntity;

    }

}