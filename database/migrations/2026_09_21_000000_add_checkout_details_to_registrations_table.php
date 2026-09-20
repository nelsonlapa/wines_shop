<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('registrations', function (Blueprint $table): void {
            $table->string('customer_name')->nullable()->after('user_id');
            $table->string('address')->nullable()->after('customer_name');
            $table->string('postal_code')->nullable()->after('address');
            $table->string('city')->nullable()->after('postal_code');
            $table->string('phone')->nullable()->after('city');
            $table->string('delivery_method')->nullable()->after('phone');
            $table->decimal('shipping_cost', 8, 2)->default(0)->after('delivery_method');
            $table->string('stripe_session_id')->nullable()->after('shipping_cost');
        });
    }

    public function down(): void
    {
        Schema::table('registrations', function (Blueprint $table): void {
            $table->dropColumn([
                'customer_name',
                'address',
                'postal_code',
                'city',
                'phone',
                'delivery_method',
                'shipping_cost',
                'stripe_session_id',
            ]);
        });
    }
};
