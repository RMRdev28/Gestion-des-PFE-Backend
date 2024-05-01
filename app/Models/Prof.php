<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Prof extends User
{
    use HasFactory;
    protected $fillable = [
        'idUser',
        'isValidator'
    ];

    public function pfeEncadre(): HasMany
    {
        return $this->hasMany(Pfe::class, 'idEns', 'id');
    }

    public function binomsEncadre()
    {
        return $this->pfeEncadre()->where('status', 'valide')->select('pfes.idBinom')->get();
    }

    /**
     * The pfe that the prof encadre
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function pfeValide(): BelongsToMany
    {
        return $this->belongsToMany(Pfe::class, 'validation_pves', 'idProf', 'idPfe');
    }


    public function jury1ForPfe(): HasMany
    {
        return $this->hasMany(Pfe::class, 'jury1');
    }

    public function jury2ForPfe(): HasMany
    {
        return $this->hasMany(Pfe::class, 'jury2');
    }

    public function juryForPfe()
    {
        return $this->jury1ForPfe->concat($this->jury2ForPfe);
    }

    public function getNumberPJY():int{
        return $this->juryForPfe()->count();
    }


    public function specialites():BelongsToMany
    {
        return $this->belongsToMany(Category::class,'prof_categories','idProf','idCategory');
    }


}
