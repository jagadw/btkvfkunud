<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LogBook extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'kegiatan',
        'tanggal',
    ];

    public function user()
    {
        return $this->belongsTo(User::class)->withTrashed();
    }
}
