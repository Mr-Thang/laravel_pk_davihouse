<?php

namespace CMS\Package\Repositories\District;

use CMS\Package\Repositories\BaseInterface;

interface DistrictRepositoryInterface extends BaseInterface
{
    public function getPaginateCount($relationship, $province, $classifyType, $limit = null);
}
