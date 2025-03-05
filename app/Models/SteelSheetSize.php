<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Relations\HasMany;

use Illuminate\Database\Eloquent\Model;

class SteelSheetSize extends Model
{
    protected $fillable = ['size'];

    public function steelSheets(): HasMany
    {
        return $this->hasMany(SteelSheet::class, 'size_id');
    }
}
