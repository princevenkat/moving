<?php

namespace App\Models;

use \App\Enums\VendorStatusEnum;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Hash;

class Vendor extends Model
{
//    //
//    protected  $primaryKey = 'user_id';
//
//    public  function scopeEligibleForPayout(Builder $query) : Builder{
//        return $query->where("status", VendorStatusEnum::Approved());
//
//    }
//
//    public function vendor() : BelongsTo{
//        return $this->belongsTo(Vendor::class, 'user_id');
//    }

    protected $fillable = [
        'user_id',
        'store_name',
        'store_phone',
        'store_address',
        'status',
    ];

    public function user()
    {
        //return $this->belongsTo(User::class);

        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    protected $attributes = [
        'status' => 'Pending', // Default status when a vendor is created
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }


}
