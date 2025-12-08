<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tenant_pages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->string('slug');
            $table->longText('content');
            $table->enum('type', ['page', 'news', 'announcement'])->default('page');
            $table->boolean('is_published')->default(false);
            $table->integer('order')->default(0);
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->timestamps();
            
            $table->unique(['tenant_id', 'slug']);
            $table->index(['tenant_id', 'is_published']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tenant_pages');
    }
};
