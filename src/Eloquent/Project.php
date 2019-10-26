<?php

namespace CMS\Package\Eloquent;

use Carbon\Carbon;
use CMS\Package\Traits\UUID;
use CMS\Package\Traits\BaseEloquent;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Project extends Model
{
    use UUID, Notifiable, BaseEloquent;

    protected $fillable = [
        'id',
        'title',
        'slug',
        'overview',
        'incentives',
        'project_info',
        'price_min',
        'price_max',
        'acreage_min',
        'acreage_max',
        'partners',
        'overview_images',
        'position_images',
        'ground_images',
        'sample_house_images',
        'progress_images',
        'videos',
        'address',
        'latitude',
        'longitude',
        'geo_json_code',
        'province_id',
        'district_id',
        'ward_id',
        'note',
        'creator_id',
        'opened_sale_at',
        'closed_sale_at',
        'published_at',
        'expired_at',
    ];

    protected $casts = [
        'overview_images'       => 'array',
        'position_images'       => 'array',
        'ground_images'         => 'array',
        'sample_house_images'   => 'array',
        'progress_images'       => 'array'
    ];

    public function setTitleAttribute($title)
    {
        $this->attributes['title'] = $title;
        $this->attributes['slug'] = str_slug($title);
    }

    public function setPriceMinAttribute($price_min)
    {
        $this->attributes['price_min'] = (str_replace(['$', ','], '', $price_min));
    }

    public function setPriceMaxAttribute($price_max)
    {
        $this->attributes['price_max'] = (str_replace(['$', ','], '', $price_max));
    }

    public function setSortOrderAttribute($sort_order)
    {
        $table = \DB::table('projects')->latest()->pluck('sort_order')->first();
        $this->attributes['sort_order'] = $table + 1;
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    public function classifyTypes()
    {
        return $this->belongsToMany(
            Classify::class,
            'project_type',
            'project_id',
            'type_id'
        );
    }

    public function classifyPropertyTypes()
    {
        return $this->belongsToMany(
            Classify::class,
            'project_property_type',
            'project_id',
            'type_id'
        );
    }

    public function classifyUtilities()
    {
        return $this->belongsToMany(
            Classify::class,
            'project_utility',
            'project_id',
            'utility_id'
        );
    }

    public function province()
    {
        return $this->belongsTo(Province::class, 'province_id');
    }

    public function district()
    {
        return $this->belongsTo(District::class);
    }

    public function ward()
    {
        return $this->belongsTo(Ward::class, 'ward_id');
    }

    public function scopePublished()
    {
        return [
            ['published_at', '<=', Carbon::now()],
            ['expired_at', '>=', Carbon::now()],
            ['status', 'published'],
        ];
    }
}
