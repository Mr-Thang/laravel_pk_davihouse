<?php

namespace CMS\Package\Eloquent;

use Illuminate\Database\Eloquent\Model;
use CMS\Package\Traits\UUID;

class Contact extends Model
{
    use UUID;

    protected $fillable = [
        'id',
        'full_name',
        'email',
        'phone_number',
        'notes',
        'status'
    ];

    public function properties()
    {
        return $this->belongsTo(Property::class);
    }
}
