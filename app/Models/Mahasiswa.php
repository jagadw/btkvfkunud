<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Mahasiswa extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'nama',
        'inisial_residen',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
