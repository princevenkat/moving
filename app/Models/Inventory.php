<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Inventory extends Model
{
    public function Items():hasMany
    {
        return $this->hasMany(InventoryItems::class);
    }
}
