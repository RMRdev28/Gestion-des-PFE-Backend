<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SuiviPfe extends Model
{
    use HasFactory;
    protected $table = "suivi_pfes";
    /**
     * Get the binom that owns the SuiviPfe
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function binom(): BelongsTo
    {
        return $this->belongsTo(Binom::class, 'idBinom', 'id');
    }


}
