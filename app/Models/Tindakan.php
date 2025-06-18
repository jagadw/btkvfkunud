<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tindakan extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'pasien_id',
        'operator_id',
        'asisten1_id',
        'asisten2_id',
        'on_loop_id',
        'tanggal_operasi',
        'relealisasi_tindakan',
        'kesesuaian'
    ];

    public function pasien()
    {
        return $this->belongsTo(Pasien::class)->withTrashed();
    }
    public function operator()
    {
        return $this->belongsTo(User::class, 'operator_id', 'id')->withTrashed();
    }
    public function asisten1()
    {
        return $this->belongsTo(User::class, 'asisten1_id','id')->withTrashed();
    }
    public function asisten2()
    {
    return $this->belongsTo(User::class, 'asisten2_id', 'id')->withTrashed();
    }
    public function onLoop()
    {
        return $this->belongsTo(User::class, 'on_loop_id','id')->withTrashed();
    }

    public function fotoTindakan()
    {
        return $this->hasOne(FotoTindakan::class);
    }

    public function conference()
    {
        return $this->hasOne(Conference::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($tindakan) {
            $tindakan->fotoTindakan()->delete();
        });

        static::restoring(function ($tindakan) {
            $tindakan->fotoTindakan()->restore();
        });
    }
}
