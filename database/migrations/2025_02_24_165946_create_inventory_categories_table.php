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
        Schema::create('inventory_items', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('inventory_id')->index()->constrained();
            $table->foreignId('parent_id')->nullable()->index()->constrained('inventory_items');

            $table->json('options')->nullable(); // Store option names (e.g., type, size, weight)
            $table->json('option_values')->nullable(); // Store selectable values per option

            $table->boolean('active')->default(true);
            $table->timestamps();
            $table->string('image')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventory_items');
    }
};
