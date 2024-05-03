<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Binom;
use App\Models\Proposition;
use App\Models\Chat;
use App\Models\Message;


class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'fname',
        'lname',
        'email',
        'password',
        'typeUser',

    ];
    /**
     * Get all of the binoms for the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    // public function binoms():HasMany
    // {
    //     return $this->hasMany(Binom::class, 'idEns', 'id');
    // }

    /**
     * Get all of the propostions for the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function propositions(): HasMany
    {
        return $this->hasMany(Proposition::class, 'idUser', 'id');
    }






    /**
     * Get all of the comments for the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */



    /**
     * Get the user associated with the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    /**
     * Get the user detail associated with the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function userDetail(): HasOne
    {
        if ($this->typeUser == 0) {
            return $this->hasOne(Student::class, 'idUser', 'id');
        } else if ($this->typeUser == 1) {
            return $this->hasOne(Prof::class, 'idUser', 'id');
        } else {
            return $this->hasOne(Admin::class, 'idUser', 'id');
        }
    }

    /**
     * Get all of the messages for the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function messages(): HasMany
    {
        return $this->hasMany(Message::class, 'idSender', 'id');
    }




    /**
     * The chats that belong to the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function chats(): BelongsToMany
    {
        return $this->belongsToMany(Chat::class, 'chats', 'idEns', 'id');
    }



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
        'password' => 'hashed',
    ];
}
