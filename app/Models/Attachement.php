<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Attachement extends Model
{
    use HasFactory;


    /**
     * Get the proposition that owns the Attachement
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function proposition(): BelongsTo
    {
        return $this->belongsTo(Proposition::class, 'idProp', 'id');
    }
}
