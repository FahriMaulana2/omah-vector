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
        Schema::create('portfolio_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('portfolios', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('client_name')->nullable();
            $table->string('project_url')->nullable();
            $table->string('featured_image_path')->nullable();
            $table->text('excerpt')->nullable();
            $table->longText('description')->nullable();
            $table->date('project_date')->nullable();
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_active')->default(true);
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('portfolio_category_portfolio', function (Blueprint $table) {
            $table->foreignId('portfolio_category_id')->constrained('portfolio_categories')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('portfolio_id')->constrained('portfolios')->cascadeOnUpdate()->cascadeOnDelete();
            $table->primary(['portfolio_category_id', 'portfolio_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('portfolio_category_portfolio');
        Schema::dropIfExists('portfolios');
        Schema::dropIfExists('portfolio_categories');
    }
};
