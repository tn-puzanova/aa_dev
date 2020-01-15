<?php

namespace common\auth\rbac;

use yii\rbac\DbManager;

class RoleManager
{
    private $manager;

    public function __construct(DbManager $manager)
    {
        $this->manager = $manager;
    }

    public function assign($userId, $name): void
    {
        if (!$role = $this->manager->getRole($name)) {
            throw new \DomainException('Такой роли "' . $name . '" не существует');
        }
        $this->manager->revokeAll($userId);
        $this->manager->assign($role, $userId);
    }
}