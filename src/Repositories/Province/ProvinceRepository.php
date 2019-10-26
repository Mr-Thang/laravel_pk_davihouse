<?php

namespace CMS\Package\Repositories\Province;

use CMS\Package\Repositories\BaseRepository;

class ProvinceRepository extends BaseRepository
{
    public function model()
    { }

    public function getPaginateCount($relationship, $classifyType, $limit = null)
    {
        return $this->_model->withCount([$relationship => function ($query) use ($classifyType) {
            $query->whereHas('classifyTypes', function ($query) use ($classifyType) {
                if ($classifyType) {
                    $query->whereIn('slug', $this->descending(collect($classifyType)));
                }
            });
        }])->orderBy('properties_count', 'DESC')->take($limit)->get();
    }

    public function countMostProperty()
    {
        return $this->_model->wherePropertyProvince()->with(['districts' => function ($query) {
            $query->withCount('properties')->orderBy('properties_count', 'DESC');
        }])->get()->map(function ($query) {
            $query->setRelation('districts', $query->districts->where('properties_count', '>', '0')->take(5));
            return $query;
        });
    }
}
