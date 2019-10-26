<?php

namespace CMS\Package\Repositories\Province;

use CMS\Package\Repositories\BaseInterface;

interface ProvinceRepositoryInterface extends BaseInterface
{
    public function getPaginateCount($relationship, $classifyType, $limit = null);

    public function countMostProperty();
}
