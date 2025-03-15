<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InventoryItems extends Model
{
        public function parent(): BelongsTo
        {
            return $this->belongsTo(InventoryItems::class, 'parent_id');
        }
}
