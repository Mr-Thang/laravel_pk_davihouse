<?php

namespace CMS\Package\Eloquent;

use Illuminate\Database\Eloquent\Model;
use CMS\Package\Traits\UUID;

class Country extends Model
{
    use UUID;

    protected $fillable = [
        'country_code',
        'common_name',
        'formal_name',
        'country_type',
        'country_sub_type',
        'sovereignty',
        'capital',
        'currency_code',
        'currency_name',
        'telephone_code',
        'country_code3',
        'country_number',
        'internet_country_code',
        'sort_order',
    ];

    public function provinces()
    {
        return $this->hasMany(Province::class);
    }
    public function scopeByKeyword($query, $keyword)
    {
        return $query->where('common_name', 'LIKE', "{$keyword}%");
    }
}
