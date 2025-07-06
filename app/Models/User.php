<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'akses_semua',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function mahasiswa()
    {
        return $this->hasOne(Mahasiswa::class);
    }
    
    public function asistens()
    {
        return $this->hasMany(TindakanAsisten::class);
    }
    
   public function dpjp(){
        return $this->hasOne(Dpjp::class);
   }

    public function logBook()
    {
        return $this->hasMany(LogBook::class);
    }
    
    protected static function boot()
    {
        parent::boot();

        // static::deleting(function ($user) {
        //     $user->mahasiswa()->delete();
        //     $user->logBook()->each(function ($logBook) {
        //         $logBook->delete();
        //     });
        // });

        // static::restoring(function ($user) {
        //     $user->mahasiswa()->restore();
        //     $user->logBook()->each(function ($logBook) {
        //         $logBook->restore();
        //     });
        // });
    }
}
