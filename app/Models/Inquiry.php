<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Inquiry extends Model {
    use HasFactory;

    protected $fillable = [
        'service_type', 'current_country', 'current_zip', 'current_city',
        'destination_country', 'destination_zip', 'destination_city', 'email',
        'current_home_type', 'floor', 'rooms', 'square_meters',
        'has_elevator', 'distance_meters', 'num_steps', 'impeded_details'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }


}
