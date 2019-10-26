<?php

namespace CMS\Package\Eloquent;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $fillable = [
        'read_at'
    ];

    public function scopeWhereRelationshipProject()
    {
        return $this->whereNotifiableType(Project::class)->whereReadAt(null);
    }
}
