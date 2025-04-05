<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('inquiries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable();
            $table->enum('service_type', ['moving', 'cleaning', 'moving-handover-cleaning']);
            $table->string('current_country');
            $table->string('current_zip');
            $table->string('current_city');
            $table->string('destination_country')->nullable();
            $table->string('destination_zip')->nullable();
            $table->string('destination_city')->nullable();
            $table->string('email');
            $table->enum('status', ['draft', 'submitted'])->default('draft');
            $table->timestamps();
        });
    }

    public function down() {
        Schema::dropIfExists('inquiries');
    }
};
