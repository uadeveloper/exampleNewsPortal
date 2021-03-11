<?php

namespace App\Repository;

use App\Entity\User;
use App\Entity\UserPermission;
use SiteCore\AbstractEntity;
use SiteCore\PDORepository;

class UserPermissionRepository extends PDORepository
{

    public function loadAllUsersPermissions(): array
    {

        $sth = $this->db->prepare("SELECT * FROM users_permissions");
        $sth->execute();
        $rawDbPermissions = $sth->fetchAll(\PDO::FETCH_ASSOC);

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
        $sth->execute([
            ':id' => $id
        ]);
        $dbUserRaw = $sth->fetch(\PDO::FETCH_ASSOC);

        if ($dbUserRaw) {
            $entity = $this->bindEntity($dbUserRaw);
        }

        return $entity;

    }

    /**
     * Получение списка прав определенного пользователя
     * @param int $userId
     * @return array
     * @throws \Exception
     */
    public function loadUserPermissions(int $userId): ?array
    {

        $sth = $this->db->prepare("SELECT * FROM users_permissions_rel WHERE user_id = :user_id");
        $sth->execute([
            ":user_id" => $userId
        ]);
        $rawUserPermissionsRels = $sth->fetchAll(\PDO::FETCH_ASSOC);

        $permissions = [];

        if ($rawUserPermissionsRels) {

            foreach ($rawUserPermissionsRels as $permissionRel) {
                $permissions[] = $this->findById($permissionRel['permission_id']);
            }

        }

        return $permissions;

    }

    public function saveUserPermissionsRels(User $userEntity)
    {

        $sth = $this->db->prepare("DELETE FROM users_permissions_rel WHERE user_id = :user_id LIMIT 1");
        $sth->execute([
            ':user_id' => $userEntity->getId()
        ]);

        foreach ($userEntity->getUserPermissions() as $userPermission) {
            $this->saveUserPermissionRel($userPermission, $userEntity);
        }

    }

    public function saveUserPermissionRel(UserPermission $userPermission, User $userEntity)
    {
        $sth = $this->db->prepare("INSERT INTO users_permissions_rel (user_id, permission_id) VALUES (:user_id, :permission_id)");
        $sth->execute([
            'user_id' => $userEntity->getId(),
            'permission_id' => $userPermission->getId()
        ]);
        # This block need use transaction :)
        $userEntity->setId($this->db->lastInsertId());
    }

    private function bindEntity(array $data)
    {

        if (!$data) {
            throw new \Exception("Bind data entity is empty.");
        }

        $entity = new UserPermission();
        $entity->loadByArray($data);

        return $entity;

    }


}