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
            $table->enum('new_home_type', ['House', 'Apartment', 'Shared Flat', 'Storage', 'Office'])->nullable();
            $table->string('new_home_floor')->nullable();
            $table->integer('new_home_rooms')->nullable();
            $table->integer('new_home_square_meters')->nullable();
            $table->boolean('new_home_has_elevator')->default(false);
            $table->boolean('new_home_long_distance')->default(false);
            $table->integer('new_home_distance_meters')->nullable();
            $table->boolean('new_home_has_steps')->default(false);
            $table->integer('new_home_num_steps')->nullable();
            $table->boolean('new_home_impeded')->default(false);
            $table->text('new_home_impeded_details')->nullable(); // Changed from boolean to text
            $table->text('new_home_notes')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inquiries');
    }
};
