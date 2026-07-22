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
        Schema::create('order_statuses', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->boolean('is_default')->default(false);
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnUpdate()->restrictOnDelete();
            $table->string('order_code')->unique();
            $table->foreignId('current_status_id')->nullable()->constrained('order_statuses')->cascadeOnUpdate()->nullOnDelete();
            $table->string('customer_name');
            $table->string('customer_email')->nullable();
            $table->string('customer_phone');
            $table->text('customer_address')->nullable();
            $table->string('customer_city')->nullable();
            $table->string('customer_state')->nullable();
            $table->string('customer_country')->nullable();
            $table->string('source')->default('web');
            $table->decimal('total_amount', 12, 2)->nullable();
            $table->text('notes')->nullable();
            $table->string('whatsapp_redirect_url')->nullable();
            $table->timestamp('whatsapp_sent_at')->nullable();
            $table->timestamp('estimated_delivery_at')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->boolean('is_confirmed')->default(false);
            $table->softDeletes();
            $table->timestamps();

            $table->index('customer_phone');
            $table->index('current_status_id');
            $table->index('created_at');
        });

        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('product_id')->nullable()->constrained('products')->cascadeOnUpdate()->nullOnDelete();
            $table->string('product_name');
            $table->string('product_sku')->nullable();
            $table->decimal('unit_price', 12, 2)->nullable();
            $table->unsignedInteger('quantity')->default(1);
            $table->decimal('total_price', 12, 2)->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();

            $table->index('order_id');
            $table->index('product_id');
        });

        Schema::create('order_status_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('order_status_id')->nullable()->constrained('order_statuses')->cascadeOnUpdate()->nullOnDelete();
            $table->foreignId('changed_by_user_id')->nullable()->constrained('users')->cascadeOnUpdate()->nullOnDelete();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index('order_id');
            $table->index('order_status_id');
            $table->index('changed_by_user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_status_histories');
        Schema::dropIfExists('order_items');
        Schema::dropIfExists('orders');
        Schema::dropIfExists('order_statuses');
    }
};
