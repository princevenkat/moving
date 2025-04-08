<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'categories',
        'options',
        'image',
    ];

    protected $casts = [
        'options' => 'array',
        'option_values' => 'array',
    ];
    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }


    public function options()
    {
        return $this->hasMany(ProductOption::class);
    }


}

