<?php

namespace CMS\Package\Repositories\Project;

use CMS\Package\Repositories\BaseInterface;

interface ProjectRepositoryInterface extends BaseInterface
{
    public function dataTableUtility();

    public function createRelationship(array $attributes, $property_type, $project_property_type, $project_utility);

    public function updateRelationship(array $attributes, $slug, $property_type, $project_property_type, $project_utility);

    public function loadData($slug, $request, $limit);
}
