<?php

namespace CMS\Package\Repositories\Property;

use CMS\Package\Repositories\BaseInterface;

interface PropertyRepositoryInterface extends BaseInterface
{
    public function loadData($slug, $request, $limit);

    public function loadDataWhereHas($relationship = null, $slug, $request, $limit);

    public function loadDataAddress($province, $district = null, $request, $classifyType, $limit);
}
