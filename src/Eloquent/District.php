<?php

namespace CMS\Package\Eloquent;

use Illuminate\Database\Eloquent\Model;
use CMS\Package\Traits\UUID;

class District extends Model
{
    use UUID;

    protected $fillable = [
        'name',
        'slug',
        'type',
        'tati_long_tude',
        'province_id',
        'sort_order',
    ];

    public $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public $timestamps = false;

    protected $hidden = [];

    public function province()
    {
        return $this->belongsTo(Province::class);
    }

    public function wards()
    {
        return $this->hasMany(Ward::class);
    }

    public function properties()
    {
        return $this->hasMany(Property::class);
    }

    public function projects()
    {
        return $this->hasMany(Project::class, 'district_id');
    }
}
