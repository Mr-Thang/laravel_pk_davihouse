<?php

namespace CMS\Package\Repositories\User;

use CMS\Package\Repositories\BaseInterface;

interface UserRepositoryInterface extends BaseInterface
{
    public function updateRole($id, $relationshipId = null);

    public function createMany(array $attribute, $relationshipId);

    public function getData($eloquent);

    public function login(array $attributes, $remember);

    public function logout();
}
