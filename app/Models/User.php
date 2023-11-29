<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


    public function summaries()
    {
        return $this->hasMany(Summary::class, 'created_by');
    }


    public function expenses()
    {
        return $this->hasMany(Expenses::class);
    }

    public function notes()
    {
        return $this->hasMany(Notes::class);
    }
    public function btypes()
    {
        return $this->hasMany(Btype::class); // Assuming you have a "Btype" model
    }

    public function types()
    {
        return $this->hasMany(Types::class); // Assuming you have a "Btype" model
    }


    public function banks()
    {
        return $this->hasMany(Bank::class, 'created_by');
    }
}
