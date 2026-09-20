<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('registrations', function (Blueprint $table): void {
            $table->dropUnique('registrations_user_id_event_id_unique');
        });
    }

    public function down(): void
    {
        Schema::table('registrations', function (Blueprint $table): void {
            $table->unique(['user_id', 'event_id']);
        });
    }
};
