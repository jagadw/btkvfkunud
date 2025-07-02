<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Dpjp extends Model
{
    protected $fillable = [
        'user_id',
        'nama',
        'inisial_residen',
        'tempat_lahir',
        'tanggal_lahir',
        'status',
        'alamat',
        'ttd',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
