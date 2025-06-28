<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TindakanAsisten extends Model
{
    protected $fillable = [
        'tindakan_id',
        'user_id',
        'tipe',
        'urutan',
        'role',
        'deskripsi',
    ];

    public function tindakan()
    {
        return $this->belongsTo(Tindakan::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
