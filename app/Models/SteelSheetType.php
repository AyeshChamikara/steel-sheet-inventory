<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Relations\HasMany;

use Illuminate\Database\Eloquent\Model;

class SteelSheetType extends Model
{
    protected $fillable = ['name'];

    public function steelSheets(): HasMany
    {
        return $this->hasMany(SteelSheet::class, 'type_id');
    }
}
