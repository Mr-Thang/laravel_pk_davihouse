<?php

namespace CMS\Package\Eloquent;

use Illuminate\Database\Eloquent\Model;
use CMS\Package\Traits\UUID;

class Ward extends Model
{
    use UUID;

    protected $fillable = [
        'id',
        'name',
        'slug',
        'type',
        'tati_long_tude',
        'district_id',
        'sort_order',
    ];

    public $timestamps = false;

    public function district()
    {
        return $this->belongsTo(District::class);
    }

    public function properties()
    {
        return $this->hasMany(Property::class);
    }

    public function projects()
    {
        return $this->hasMany(Project::class);
    }
}
