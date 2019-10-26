<?php

namespace CMS\Package\Repositories\Role;

use CMS\Package\Repositories\BaseInterface;

interface RoleRepositoryInterface extends BaseInterface
{
    public function createMany(array $attribute, $relationshipId);

    public function updateMany(array $attribute, $id, $relationshipId);
}
