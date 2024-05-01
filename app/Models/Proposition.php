<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Category;
use App\Models\Demmande;
use App\Models\Attachement;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Proposition extends Model
{
    use HasFactory;

    protected $fillable = [
        'idUser',
        'title',
        'description',
        'type',
        'status',
        'level',
    ];



    /**
     * Get the user that owns the Proposition
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
    return $this->belongsTo(User::class, 'idUser');
    }

    public function details()
    {
        return $this->user()->with(['userDetail.binom']);
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class,'proposition_categories','idProp','idCategory');
    }





    /**
     * Get all of the demmandes for the Proposition
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function demmandes(): HasMany
    {
        return $this->hasMany(Demmande::class, 'idProp', 'id');
    }


    /**
     * The criters that belong to the Proposition
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function criters(): BelongsToMany
    {
        return $this->belongsToMany(Criter::class, 'props_criters', 'idProp', 'idCriter');
    }

    /**
     * Get all of the attachemnts for the Proposition
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function attachemnts(): HasMany
    {
        return $this->hasMany(Attachement::class, 'idProp', 'id');
    }
}
