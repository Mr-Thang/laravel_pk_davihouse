<?php

namespace CMS\Package\Repositories\Property;

use CMS\Package\Eloquent\Classify;
use CMS\Package\Repositories\BaseRepository;

class PropertyRepository extends BaseRepository
{
    public function model()
    { }

    public function loadData($slug, $request, $limit)
    {
        return $this->_model
            ->whereHas('classifyTypes', function ($query) use ($slug) {
                if ($this->descending($slug)) {
                    $query->whereIn('slug', $this->descending($slug));
                }
            })
            ->orWhereHas('province', function ($query) use ($slug) {
                $query->whereSlug($slug);
            })
            ->orderBySearch($request['sort'])
            ->whereClassifyType($request['danh-muc'])
            ->wherePriceMinAndMax($request['price'])
            ->whereProvince($request['tinh-thanh'])
            ->whereDistrict($request['quan-huyen'])
            ->whereAcreage($request['acreage'])
            ->paginate($limit);
    }
    public function loadDataAddress($province, $district = null, $request, $classifyType, $limit)
    {
        return $this->_model
            ->whereHas('classifyTypes', function ($query) use ($classifyType) {
                if ($this->descending($classifyType)) {
                    $query->whereIn('slug', $this->descending($classifyType));
                }
            })
            ->whereHas('province', function ($query) use ($province) {
                $query->whereSlug($province);
            })
            ->whereHas('district', function ($query) use ($district) {
                if ($district) {
                    $query->whereSlug($district);
                }
            })
            ->orderBySearch($request['sort'])
            ->whereClassifyType($request['danh-muc'])
            ->wherePriceMinAndMax($request['price'])
            ->whereProvince($request['tinh-thanh'])
            ->whereDistrict($request['quan-huyen'])
            ->whereAcreage($request['acreage'])
            ->paginate($limit);
    }

    public function loadDataWhereHas($relationship = null, $slug, $request, $limit)
    {
        return $this->_model
            ->whereHas($relationship, function ($query) use ($slug) {
                $query->whereSlug($slug);
            })
            ->whereHas('classifyTypes', function ($query) {
                $query->whereIn('slug', $this->descending(Classify::BUY_SELL));
            })
            ->orderBySearch($request)->paginate($limit);
    }
}
