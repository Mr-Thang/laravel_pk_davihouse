<?php

namespace CMS\Package\Traits;

use Carbon\Carbon;
use CMS\Package\Eloquent\Option;
use CMS\Package\Eloquent\District;
use CMS\Package\Eloquent\Province;
use CMS\Package\Helpers\AppHelper;

trait BaseEloquent
{
    protected $orderBySearch = [
        'new'           => 'created_at desc',
        'old'           => 'created_at asc',
        'price_min'     => 'price_min asc',
        'price_max'     => 'price_min desc',
    ];

    public function getFormatPriceAttribute()
    {
        return AppHelper::showPrice($this->price_min, $this->price_max) . ' ' . AppHelper::showPriceType($this->price_min, $this->price_max);
    }

    public function getAcreagePriceAttribute()
    {
        return AppHelper::showAcreagePrice($this->price_min, $this->price_max, $this->acreage_min, $this->acreage_max);
    }
    public function getFormatAcreageAttribute()
    {
        return AppHelper::showAcreage($this->acreage_min, $this->acreage_max);
    }

    public function getStatusActiveAttribute()
    {
        echo $textStatus = "";

        switch ($this->status) {
            case 'published':
                $textStatus .= '<span class="badge badge-success">Xuất bản</span>';
                break;
            case 'pending':
                $textStatus .= "Chờ duyệt";
                break;
            case 'draft':
                $textStatus .= "Bản nháp";
                break;
            case 'return':
                $textStatus .= "Trả lại";
                break;
            default:
                # code...
                break;
        }

        return  $textStatus;
    }

    public function getFormatCreatedAtAttribute()
    {
        return $this->created_at->format('d/m/Y');
    }

    public function scopePublished($query)
    {
        return $query->where([
            ['published_at', '<=', Carbon::now()],
            ['expired_at', '>=', Carbon::now()],
            ['status', 'published'],
        ]);
    }

    public function scopeWhereOpenedSale($query)
    {
        return $query->where([
            ['opened_sale_at', '<=', Carbon::now()],
            ['closed_sale_at', '>=', Carbon::now()],
            ['status', 'published'],
        ]);
    }

    public function scopeOrderBySearch($query, $value)
    {
        if ($value) {
            return $query->orderByRaw($this->orderBySearch[$value]);
        }
    }

    public function scopeWherePriceMinAndMax($query, $value)
    {
        if ($value && $value != "null") {
            $valueWhere = Option::filterSearchPrice()->where('slug', $value)->first();
            return $query->where([
                ['price_min', '>=', $valueWhere['price_min']],
                ['price_max', '<=', $valueWhere['price_max']]
            ]);
        }
    }

    public function scopeWhereProvince($query, $value)
    {
        if ($value && $value != "null") {
            $findProvince = Province::whereSlug($value)->firstOrFail();
            return $query->whereProvinceId($findProvince->id);
        }
    }

    public function scopeWhereDistrict($query, $value)
    {
        if ($value && $value != "null") {
            $findDistrict = District::whereSlug($value)->firstOrFail();
            return $query->whereDistrictId($findDistrict->id);
        }
    }

    public function scopeWhereAcreage($query, $value)
    {
        if ($value && $value != "null") {
            $valueWhere = Option::filterSearchAcreage()->where('slug', $value)->first();

            return $query->where([
                ['acreage_min', '>=', $valueWhere['acreage_min']],
                ['acreage_max', '<=', $valueWhere['acreage_max']]
            ]);
        }
    }
    public function scopeWhereClassifyType($query, $value)
    {
        if ($value && $value != "null") {
            return $query->whereHas('classifyTypes', function ($query) use ($value) {
                $query->whereIn('slug', AppHelper::descending($value));
            });
        };
    }

    public function scopeWhereDefault($query)
    {
        return $query->where([
            ['published_at', '<=', Carbon::now()],
            ['expired_at', '>=', Carbon::now()],
            ['status', 'published'],
        ]);
    }
}
