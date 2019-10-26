<?php

namespace CMS\Package\Repositories\Permission;

use Illuminate\Support\Arr;
use CMS\Package\Repositories\BaseRepository;

class PermissionRepository extends BaseRepository
{
    public function model()
    { }

    public function selectRoute()
    {
        $permissions = $this->all();

        $actionPermission =  [];

        foreach ($permissions as $item) {
            $actionPermission = Arr::prepend($actionPermission, $item->action);
        }

        $app = app();
        $routes = $app->routes->getRoutes();

        $route = [];
        foreach ($routes as $value) {
            if ($value->getName() != null) {
                $route = Arr::prepend($route, $value->getName(), $value->getName());
            }
        }

        return array_diff($route, $actionPermission);
    }
}
