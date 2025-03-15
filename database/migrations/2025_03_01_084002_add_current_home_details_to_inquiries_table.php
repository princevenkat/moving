<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::table('inquiries', function (Blueprint $table) {
//            $table->enum('current_home_type', ['house', 'apartment', 'shared_flat', 'storage', 'office'])->nullable();
            $table->enum('current_home_type', ['House', 'Apartment', 'Shared Flat', 'Storage', 'Office'])->nullable();
            $table->string('floor')->nullable();
            $table->integer('rooms')->nullable();
            $table->integer('square_meters')->nullable();
            $table->boolean('has_elevator')->default(false);
            $table->boolean('long_distance')->default(false);
            $table->integer('distance_meters')->nullable();
            $table->boolean('has_steps')->default(false);
            $table->integer('num_steps')->nullable();
            $table->boolean('impeded')->default(false);
            $table->text('impeded_details')->nullable(); // Changed from boolean to text
            $table->text('notes')->nullable();
        });
    }

    public function down() {
        Schema::dropIfExists('inquiries');
//        Schema::table('inquiries', function (Blueprint $table) {
//            $table->dropColumn([
//                'current_home_type', 'floor', 'rooms', 'square_meters',
//                'accessibility_long_distance', 'accessibility_distance_meters',
//                'accessibility_has_steps', 'accessibility_steps',
//                'accessibility_impeded', 'accessibility_notes'
//            ]);
//        });
    }
};
