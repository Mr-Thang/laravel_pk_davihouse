<?php

namespace CMS\Package\Repositories\District;

use CMS\Package\Repositories\BaseRepository;

class DistrictRepository extends BaseRepository
{
    public function model()
    { }
    
    public function getPaginateCount($relationship, $province, $classifyType, $limit = null)
    {
        return $this->_model->whereHas('province', function ($query) use ($province) {
            $query->whereSlug($province);
        })->withCount([$relationship => function ($query) use ($classifyType) {
            $query->whereHas('classifyTypes', function ($query) use ($classifyType) {
                $query->whereIn('slug', $this->descending(collect($classifyType)));
            });
        }])->orderBy('properties_count', 'DESC')->take($limit)->get();
    }
}
