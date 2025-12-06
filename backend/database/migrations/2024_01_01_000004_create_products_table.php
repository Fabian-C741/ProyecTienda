<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->onDelete('cascade');
            $table->foreignId('category_id')->nullable()->constrained()->onDelete('set null');
            $table->string('name');
            $table->string('slug');
            $table->string('sku')->unique();
            $table->text('description')->nullable();
            $table->text('short_description')->nullable();
            $table->decimal('price', 10, 2);
            $table->decimal('compare_price', 10, 2)->nullable();
            $table->decimal('cost', 10, 2)->nullable();
            $table->integer('stock')->default(0);
            $table->integer('low_stock_threshold')->default(5);
            $table->boolean('track_inventory')->default(true);
            $table->boolean('allow_backorder')->default(false);
            $table->json('images')->nullable();
            $table->string('featured_image')->nullable();
            $table->json('variants')->nullable();
            $table->json('attributes')->nullable();
            $table->float('weight')->nullable();
            $table->string('weight_unit')->default('kg');
            $table->json('dimensions')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamp('published_at')->nullable();
            $table->integer('views_count')->default(0);
            $table->timestamps();
            $table->softDeletes();
            
            $table->unique(['tenant_id', 'slug']);
            $table->index(['tenant_id', 'is_active', 'published_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
