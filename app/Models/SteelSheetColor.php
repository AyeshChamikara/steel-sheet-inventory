<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Relations\HasMany;

use Illuminate\Database\Eloquent\Model;

class SteelSheetColor extends Model
{
    protected $fillable = ['color'];

    public function steelSheets(): HasMany
    {
        return $this->hasMany(SteelSheet::class, 'color_id');
    }
}
