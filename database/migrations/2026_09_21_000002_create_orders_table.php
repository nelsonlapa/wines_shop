<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('orders')) {
            Schema::create('orders', function (Blueprint $table): void {
                $table->id();
                $table->string('order_reference')->unique();
                $table->foreignId('user_id')->constrained()->cascadeOnDelete();
                $table->string('customer_name')->nullable();
                $table->string('address')->nullable();
                $table->string('postal_code')->nullable();
                $table->string('city')->nullable();
                $table->string('phone')->nullable();
                $table->string('delivery_method')->nullable();
                $table->decimal('shipping_cost', 8, 2)->default(0);
                $table->string('stripe_session_id')->nullable()->unique();
                $table->string('status')->default('paid');
                $table->timestamps();
            });
        } else {
            if (!Schema::hasColumn('orders', 'order_reference')) {
                Schema::table('orders', fn (Blueprint $table) => $table->string('order_reference')->nullable()->unique());
            }
            foreach (['customer_name', 'address', 'postal_code', 'city', 'phone', 'delivery_method'] as $column) {
                if (!Schema::hasColumn('orders', $column)) {
                    Schema::table('orders', fn (Blueprint $table) => $table->string($column)->nullable());
                }
            }
        }

        if (!Schema::hasColumn('registrations', 'order_id')) {
            Schema::table('registrations', function (Blueprint $table): void {
                $table->foreignId('order_id')->nullable()->after('id')->constrained()->nullOnDelete();
            });
        }

        $groups = DB::table('registrations')
            ->select('order_reference', 'user_id', 'customer_name', 'address', 'postal_code', 'city', 'phone', 'delivery_method', 'shipping_cost', 'stripe_session_id', 'status', 'created_at', 'updated_at')
            ->whereNotNull('order_reference')
            ->whereNotExists(function ($query): void {
                $query->select(DB::raw(1))
                    ->from('orders')
                    ->whereColumn('orders.order_reference', 'registrations.order_reference');
            })
            ->groupBy('order_reference')
            ->get();

        foreach ($groups as $group) {
            $orderId = DB::table('orders')->insertGetId([
                'order_reference' => $group->order_reference,
                'number' => $group->order_reference,
                'user_id' => $group->user_id,
                'customer_name' => $group->customer_name,
                'address' => $group->address,
                'postal_code' => $group->postal_code,
                'city' => $group->city,
                'phone' => $group->phone,
                'delivery_method' => $group->delivery_method,
                'shipping_cost' => $group->shipping_cost,
                'subtotal' => 0,
                'total' => $group->shipping_cost,
                'shipping_name' => $group->customer_name ?? '',
                'shipping_address' => $group->address ?? '',
                'shipping_city' => $group->city ?? '',
                'shipping_postcode' => $group->postal_code ?? '',
                'shipping_country' => 'PT',
                'stripe_session_id' => $group->stripe_session_id,
                'status' => $group->status === 'cancelled' ? 'cancelled' : 'paid',
                'created_at' => $group->created_at,
                'updated_at' => $group->updated_at,
            ]);

            DB::table('registrations')
                ->where('order_reference', $group->order_reference)
                ->update(['order_id' => $orderId]);
        }
    }

    public function down(): void
    {
        Schema::table('registrations', function (Blueprint $table): void {
            $table->dropConstrainedForeignId('order_id');
        });

        Schema::dropIfExists('orders');
    }
};
