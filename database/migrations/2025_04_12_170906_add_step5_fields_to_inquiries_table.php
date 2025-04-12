<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('inquiries', function (Blueprint $table) {
            $table->date('moving_date')->nullable();
//            $table->string('salutation')->nullable();
//            $table->string('first_name')->nullable();
//            $table->string('last_name')->nullable();
//            $table->string('phone_number')->nullable();
            $table->boolean('thirdParty_broker')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('inquiries', function (Blueprint $table) {
            //
        });
    }
};
