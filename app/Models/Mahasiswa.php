<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Mahasiswa extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'nim',
        'nama',
        'inisial_residen',
        'tempat_lahir',
        'tanggal_lahir',
        'status',
        'alamat',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // protected static function boot()
    // {
    //     parent::boot();

    //     static::deleting(function ($mahasiswa) {
    //         $mahasiswa->user()->delete();
    //     });

    //     static::restoring(function ($mahasiswa) {
    //         $mahasiswa->user()->restore();
    //     });
        
    // }
}
