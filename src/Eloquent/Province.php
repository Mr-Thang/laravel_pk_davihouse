<?php

namespace CMS\Package\Eloquent;

use Illuminate\Database\Eloquent\Model;
use CMS\Package\Traits\UUID;

class Province extends Model
{
    use UUID;

    protected $cityBig = [
        'ha-noi',
        'ho-chi-minh',
        'da-nang'
    ];

    protected $fillable = [
        'name',
        'slug',
        'type',
        'telephone_code',
        'zip_code',
        'country_id',
        'country_code',
        'sort_order',
    ];

    public $timestamps = false;

    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function districts()
    {
        return $this->hasMany(District::class);
    }

    public function properties()
    {
        return $this->hasMany(Property::class);
    }

    public function projects()
    {
        return $this->hasMany(Project::class);
    }

    public function scopeWherePropertyProvince($query)
    {
        return $query->whereIn('slug', $this->cityBig);
    }
}
