<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Criter extends Model
{
    use HasFactory;


    /**
     * The proposition that belong to the Criter
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function proposition(): BelongsToMany
    {
        return $this->belongsToMany(Proposition::class, 'props_criters', 'idCriter', 'idProp');
    }
}
