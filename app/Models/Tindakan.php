<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tindakan extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'pasien_id',
        'dpjp_id',
        'on_loop_id',
        'nama_tindakan',
        'divisi',
        'diagnosa',
        'tanggal_operasi',
        'laporan_tindakan',
        'foto_tindakan',
        'verifikasi',
    ];

    public function pasien()
    {
        return $this->belongsTo(Pasien::class)->withTrashed();
    }
    public function dpjp()
    {
        return $this->belongsTo(User::class, 'dpjp_id', 'id')->withTrashed();
    }
    public function tindakanAsistens()
    {
        return $this->hasMany(TindakanAsisten::class, 'tindakan_id');
    }

    public function onLoop()
    {
        return $this->hasOne(TindakanAsisten::class, 'tindakan_id')
            ->where('tipe', 'onloop');
    }

    public function conference()
    {
        return $this->hasOne(Conference::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($tindakan) {
            $tindakan->conference()->delete();
        });

        static::restoring(function ($tindakan) {
            $tindakan->conference()->restore();
        });
    }
}
