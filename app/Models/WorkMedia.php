<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WorkMedia extends Model
{
    protected $table = 'work_media';

    protected $fillable = [
        'work_id',
        'type',
        'path',
        'sort_order',
    ];

    public function work()
    {
        return $this->belongsTo(Work::class);
    }
}
