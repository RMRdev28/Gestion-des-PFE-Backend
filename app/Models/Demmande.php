<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Demmande extends Model
{
    use HasFactory;

    protected $fillable = [
        'idProp',
        'idBinom',
        'releverNote',
        'idBinom'
    ];


    /**
     * Get the binom that owns the Demmande
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function binom(): BelongsTo
    {
        return $this->belongsTo(Binom::class, 'idBinom', 'id');
    }
}
