<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Conference extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'pasien_id',
        'tindakan_id',
        'tanggal_conference',
        'hasil_conference',
        'kesesuaian',
        'realisasi_tindakan',
    ];

    public function pasien()
    {
        return $this->belongsTo(Pasien::class);
    }

    public function tindakan()
    {
        return $this->belongsTo(Tindakan::class);
    }
}
