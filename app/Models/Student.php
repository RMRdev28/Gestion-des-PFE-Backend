<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Student extends User
{
    use HasFactory;
    protected $fillable = [
        'haveBinom',
        'specialite',
        'level',
        'section',
        'idUser',
        'uniqueCode'
    ];

    /**
     * Get all of the comments for the Student
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function binomRequest(): HasMany
    {
        return $this->hasMany(Binom::class, 'idEtu1', 'id');
    }

    /**
     * Get the user associated with the Student
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'idUser', 'id');
    }
    /**
     * Get all of the binomDemandes for the Student
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function binomDemandes(): HasMany
    {
        return $this->hasMany(Binom::class, 'idEtu2', 'id');
    }

    public function binom1(): HasOne
    {
        return $this->hasOne(Binom::class, 'idEtu1','id');
    }

    public function binom2(): HasOne
    {
        return $this->hasOne(Binom::class, 'idEtu2','id');
    }


    public function binom():HasOne
    {
        if($this->binom1()->exists()){
            return $this->binom1();
        }else{
            return $this->binom2();
        }
    }






}
