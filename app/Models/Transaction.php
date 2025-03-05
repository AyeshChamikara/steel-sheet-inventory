<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'steel_sheet_id',
        'quantity',
    ];

    public function steelSheet(): BelongsTo
    {
        return $this->belongsTo(SteelSheet::class, 'steel_sheet_id');
    }
}
