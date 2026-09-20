<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('events', function (Blueprint $table): void {
            $table->string('producer')->nullable()->after('title');
            $table->string('country')->nullable()->after('producer');
            $table->string('wine_region')->nullable()->after('country');
            $table->string('winemaker')->nullable()->after('wine_region');
            $table->decimal('alcohol_percentage', 4, 1)->nullable()->after('winemaker');
            $table->string('bottle_capacity')->nullable()->after('alcohol_percentage');
            $table->text('grapes')->nullable()->after('bottle_capacity');
        });
    }

    public function down(): void
    {
        Schema::table('events', function (Blueprint $table): void {
            $table->dropColumn([
                'producer',
                'country',
                'wine_region',
                'winemaker',
                'alcohol_percentage',
                'bottle_capacity',
                'grapes',
            ]);
        });
    }
};
