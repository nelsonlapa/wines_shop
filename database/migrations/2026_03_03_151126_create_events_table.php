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
    Schema::create('events', function (Blueprint $table) {
        $table->id();
        $table->string('title');
        $table->text('description');
        $table->dateTime('date');
        $table->string('location');
        $table->decimal('price', 8, 2)->default(0);
        $table->integer('capacity');
        $table->enum('status', ['draft', 'active', 'cancelled'])->default('draft');

        $table->foreignId('organizer_id')
              ->constrained('users')
              ->onDelete('cascade');

        $table->foreignId('category_id')
              ->constrained()
              ->onDelete('cascade');

        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
