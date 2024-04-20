<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RendezVous extends Model
{
    use HasFactory;

    /**
     * Get the binom that owns the RendezVous
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function binom(): BelongsTo
    {
        return $this->belongsTo(Binom::class, 'idBinom', 'id');
    }
}
