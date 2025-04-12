<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OptionValue extends Model
{
    use HasFactory;

    protected $fillable = ['option_id', 'value'];

    public function option()
    {
        return $this->belongsTo(ProductOption::class, 'option_id');
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_option_value');
    }
}
