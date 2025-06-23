<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Mahasiswa extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'nama',
        'nim',
        'inisial_residen',
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
