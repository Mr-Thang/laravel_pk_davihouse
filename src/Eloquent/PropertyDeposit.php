<?php

namespace CMS\Package\Eloquent;

use Illuminate\Database\Eloquent\Model;
use CMS\Package\Traits\UUID;

class PropertyDeposit extends Model
{
    use UUID;

    protected $fillable = [
        'id',
        'customer_name',
        'slug',
        'customer_phone',
        'address',
        'property_number',
        'property_digital_sheet',
        'project',
        'another_require',
        'images',
        'acreage',
        'price',
    ];

    public function setCustomerNameAttribute($title)
    {
        $this->attributes['customer_name'] = $title;
        $this->attributes['slug'] = str_slug($title);
    }
}
