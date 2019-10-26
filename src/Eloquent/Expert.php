<?php

namespace CMS\Package\Eloquent;

use Illuminate\Database\Eloquent\Model;
use CMS\Package\Traits\UUID;

class Expert extends Model
{
    use UUID;

    protected $fillable = [
        'id',
        'experience',
        'description',
        'note',
        'sort_order',
        'status',
        'creator_id',
    ];

    protected $dates = ['activated_at'];

    public function user()
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    public function classifies()
    {
        return $this->belongsToMany(
            Classify::class,
            'expert_types',
            'expert_id',
            'type_id'
        );
    }
}
