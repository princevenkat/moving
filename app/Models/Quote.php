<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Notification;
use App\Notifications\QuoteCreatedNotification;

class Quote extends Model {
    protected static function boot() {
        parent::boot();
        static::created(function ($quote) {
            Notification::send($quote->inquiry->user, new QuoteCreatedNotification($quote));
        });
    }
}
