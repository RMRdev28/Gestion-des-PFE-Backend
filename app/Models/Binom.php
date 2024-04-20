<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Binom extends Model
{
    use HasFactory;

    /**
     * Get the encadreur that owns the Binom
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function encadreur(): BelongsTo
    {
        return $this->belongsTo(User::class, 'idEns','id');
    }

    /**
     * Get all of the demmades for the Binom
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function demamdes(): HasMany
    {
        return $this->hasMany(Demmande::class, 'idBinom', 'id');
    }

    /**
     * Get the chat associated with the Binom
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function chat(): HasOne
    {
        return $this->hasOne(Chat::class, 'idBinom', 'id');
    }

    /**
     * Get all of the redezVous for the Binom
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function redezVous(): HasMany
    {
        return $this->hasMany(RendezVous::class, 'idBinom', 'id');
    }

    /**
     * Get all of the suivis for the Binom
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function suivis(): HasMany
    {
        return $this->hasMany(SuiviPfe::class, 'idBinom', 'id');
    }

}
