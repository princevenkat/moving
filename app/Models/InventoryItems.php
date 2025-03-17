<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InventoryItems extends Model
{

    use HasFactory;

    protected $fillable = [
        'name',
        'inventory_id',
        'parent_id',
        'options',
        'option_values',
        'active',
        'image',
    ];

    protected $casts = [
        'options' => 'array', // Store selected option names
        'option_values' => 'array', // Store possible values for each option
    ];

    public function parent(): BelongsTo
    {
        return $this->belongsTo(InventoryItems::class, 'parent_id');
    }
}
