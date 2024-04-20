<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',

    ];
    /**
     * Get the proposition that owns the Cateogry
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function proposition(): BelongsTo
    {
        return $this->belongsTo(Proposition::class, 'idCategory', 'id');
    }
    /**
     * Get the proposition that owns the Cateogry
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function pfe(): BelongsTo
    {
        return $this->belongsTo(Pfe::class, 'idCategory', 'id');
    }
}
