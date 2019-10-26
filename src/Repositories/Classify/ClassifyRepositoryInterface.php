<?php

namespace CMS\Package\Repositories\Classify;

use CMS\Package\Repositories\BaseInterface;

interface ClassifyRepositoryInterface extends BaseInterface
{
    public function takeRelationshipOrderBy($slug, $relationship, $columnOrderBy, $valueOrderBy = 'ASC',  $take);

    public function firstOrFailProject();
}
