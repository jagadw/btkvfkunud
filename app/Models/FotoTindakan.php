<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FotoTindakan extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'log_book_id',
        'foto',
        'keterangan',
    ];
    public function logBook()
    {
        return $this->belongsTo(LogBook::class);
    }
}
