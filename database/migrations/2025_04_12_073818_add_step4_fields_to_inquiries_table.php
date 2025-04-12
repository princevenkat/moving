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
            $table->integer('number_of_people')->nullable();
            $table->string('length_of_residence')->nullable();
            $table->integer('number_of_boxes')->nullable();

            // Additional service fields
            $table->boolean('furniture_assembly')->default(false);
            $table->boolean('furniture_lift')->default(false);
            $table->boolean('wardrobe_boxes')->default(false);
            $table->integer('wardrobe_boxes_count')->nullable();
            $table->boolean('box_packing')->default(false);
            $table->boolean('lamp_dismantling')->default(false);
            $table->integer('lamp_dismantling_count')->nullable();
            $table->boolean('item_disposal')->default(false);
            $table->boolean('floor_protection')->default(false);
            $table->integer('floor_protection_count')->nullable();
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
