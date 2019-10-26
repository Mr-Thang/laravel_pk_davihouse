<?php

namespace CMS\Package\Eloquent;

use CMS\Package\Traits\UUID;
use CMS\Package\Helpers\AppHelper;
use CMS\Package\Traits\BaseEloquent;
use Illuminate\Database\Eloquent\Model;

class Property extends Model
{
    use UUID, BaseEloquent;

    protected $fillable = [
        'id',
        'title',
        'slug',
        'type',
        'price_min',
        'price_max',
        'acreage_min',
        'acreage_max',
        'bed_rooms',
        'bath_rooms',
        'description',
        'address',
        'latitude',
        'longitude',
        'province_id',
        'district_id',
        'ward_id',
        'creator_id',
        'note',
        'sort_order',
        'status',
        'images',
        'property_width',
        'property_length',
        'way_in_length',
        'property_direction',
        'video',
        'published_at',
        'expired_at',
    ];

    protected $casts = [
        'images'       => 'array',
    ];

    public function setTitleAttribute($title)
    {
        $this->attributes['title'] = $title;
        $this->attributes['slug'] = str_slug($title);
    }

    public function classifyTypes()
    {
        return $this->belongsToMany(
            Classify::class,
            'property_type',
            'property_id',
            'type_id'
        );
    }

    public function classifyFurnitures()
    {
        return $this->belongsToMany(
            Classify::class,
            'property_furniture',
            'property_id',
            'furniture_id'
        );
    }

    public function classifyUtilities()
    {
        return $this->belongsToMany(
            Classify::class,
            'property_utility',
            'property_id',
            'utility_id'
        );
    }

    public function province()
    {
        return $this->belongsTo(Province::class);
    }

    public function district()
    {
        return $this->belongsTo(District::class);
    }

    public function ward()
    {
        return $this->belongsTo(Ward::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    public function scopeWhereClassifyType($query, $value)
    {
        if ($value) {
            return $query->whereHas('classifyTypes', function ($query) use ($value) {
                $query->whereIn('slug', AppHelper::ascendings($value));
            });
        };
    }
}
