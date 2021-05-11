<?php

namespace App\Repository;

use App\Entity\User;
use App\Entity\UserPermission;
use Exception;
use PDO;
use SiteCore\AbstractEntity;
use SiteCore\PDORepository;

/**
 * Class UserPermissionRepository
 * @package App\Repository
 * TODO: перенести общие методы
 */
class UserPermissionRepository extends PDORepository
{

    public function loadAllUsersPermissions(): array
    {

        $sth = $this->db->prepare("SELECT * FROM users_permissions");
        $sth->execute();
        $rawDbPermissions = $sth->fetchAll(PDO::FETCH_ASSOC);

        $permissions = [];

        if ($rawDbPermissions) {

            foreach ($rawDbPermissions as $permission) {
                $permissions[] = $this->bindEntity($permission);
            }

        }

        return $permissions;

    }

    public function findById(int $id): ?AbstractEntity
    {

        $sth = $this->db->prepare("SELECT * FROM users_permissions WHERE id = :id LIMIT 1");
        $sth->bindParam(':id', $id, PDO::PARAM_INT);
        $sth->execute();
        $dbUserRaw = $sth->fetch(PDO::FETCH_ASSOC);

        if (!$dbUserRaw) {
            return null;
        }

        return $this->bindEntity($dbUserRaw);

    }

    /**
     * Получение списка прав определенного пользователя
     * @param int $userId
     * @return array
     * @throws Exception
     */
    public function loadUserPermissions(int $userId): ?array
    {

        $sth = $this->db->prepare("SELECT * FROM users_permissions_rel WHERE user_id = :user_id");
        $sth->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $sth->execute();
        $rawUserPermissionsRels = $sth->fetchAll(PDO::FETCH_ASSOC);

        $permissions = [];

        if ($rawUserPermissionsRels) {

            foreach ($rawUserPermissionsRels as $permissionRel) {
                $permissions[] = $this->findById($permissionRel['permission_id']);
            }

        }

        return $permissions;

    }

    public function saveUserPermissionsRels(User $userEntity): void
    {

        $sth = $this->db->prepare("DELETE FROM users_permissions_rel WHERE user_id = :user_id LIMIT 1");
        $sth->bindParam(':user_id', $userEntity->getId(), PDO::PARAM_INT);
        $sth->execute();

        foreach ($userEntity->getUserPermissions() as $userPermission) {
            $this->saveUserPermissionRel($userPermission, $userEntity);
        }

    }

    public function saveUserPermissionRel(UserPermission $userPermission, User $userEntity): void
    {
        $sth = $this->db->prepare("INSERT INTO users_permissions_rel (user_id, permission_id) VALUES (:user_id, :permission_id)");
        $sth->bindParam(':user_id', $userEntity->getId(), PDO::PARAM_INT);
        $sth->bindParam(':permission_id', $userPermission->getId(), PDO::PARAM_INT);
        $sth->execute();
        $userEntity->setId($this->db->lastInsertId());
    }

    private function bindEntity(array $data): UserPermission
    {

        if (!$data) {
            throw new Exception("Bind data entity is empty.");
        }

        $entity = new UserPermission();
        $entity->loadByArray($data);

        return $entity;

    }


}