<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pasien extends Model
{
    use SoftDeletes;
    protected $table = 'pasiens';

    protected $fillable = [
        'nama',
        'usia',
        'nomor_rekam_medis',
        'tanggal_lahir',
        'jenis_kelamin',
        'tipe_jantung'
    ];

    public function conferences()
    {
        return $this->hasMany(Conference::class);
    }

    public function tindakans()
    {
        return $this->hasMany(Tindakan::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($pasien) {
            $pasien->conferences()->delete();
            $pasien->tindakans()->delete();
        });

        static::restoring(function ($pasien) {
            $pasien->conferences()->restore();
            $pasien->tindakans()->restore();
        });
    }
}
