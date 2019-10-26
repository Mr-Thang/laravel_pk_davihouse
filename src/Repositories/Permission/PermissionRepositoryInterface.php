<?php

namespace CMS\Package\Repositories\Permission;

use CMS\Package\Repositories\BaseInterface;

interface PermissionRepositoryInterface extends BaseInterface
{
    public function selectRoute();
}
