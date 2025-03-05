<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

use Illuminate\Database\Eloquent\Model;

class SteelSheet extends Model
{
    protected $fillable = [
        'type_id',
        'size_id',
        'color_id',
        'total_count',
    ];

    public function type(): BelongsTo
    {
        return $this->belongsTo(SteelSheetType::class, 'type_id');
    }

    public function size(): BelongsTo
    {
        return $this->belongsTo(SteelSheetSize::class, 'size_id');
    }

    public function color(): BelongsTo
    {
        return $this->belongsTo(SteelSheetColor::class, 'color_id');
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
}
