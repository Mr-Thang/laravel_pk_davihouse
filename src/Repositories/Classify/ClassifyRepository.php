<?php

namespace CMS\Package\Repositories\Classify;

use CMS\Package\Repositories\BaseRepository;

class ClassifyRepository extends BaseRepository
{
    public function model()
    { }

    public function takeRelationshipOrderBy($slug, $relationship, $columnOrderBy, $valueOrderBy = 'ASC',  $take)
    {
        return $this->_model->whereSlug($slug)->with([$relationship => function ($query) use ($columnOrderBy, $valueOrderBy, $take) {
            $query->orderBy($columnOrderBy, $valueOrderBy)->take($take)->select('slug', 'title', 'created_at');
        }])->firstOrFail();
    }

    public function firstOrFailProject()
    {
        try {
            return $this->_model->whereNull('parent_id')->whereClassType('project_type')->firstOrFail();
        } catch (\Exception $e) {
            return false;
        }
    }
}
