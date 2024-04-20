<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Pfe extends Model
{
    use HasFactory;
    protected $table = "pfes";

    /**
     * Get the category associated with the Pfe
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function category(): HasOne
    {
        return $this->hasOne(Category::class, 'idCategory', 'id');
    }
}
