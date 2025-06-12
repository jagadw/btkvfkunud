<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FotoTindakan extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'tindakan_id',
        'foto',
        'keterangan',
    ];
    public function tindakan()
    {
        return $this->belongsTo(Tindakan::class);
    }
}
