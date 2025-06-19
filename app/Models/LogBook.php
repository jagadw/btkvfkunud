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
    public function fotoTindakan()
    {
        return $this->hasOne(FotoTindakan::class, 'log_book_id');
    }

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($logBook) {
            $logBook->fotoTindakan()->delete();
        });

        static::restoring(function ($logBook) {
            $logBook->fotoTindakan()->restore();
        });
    }
}
