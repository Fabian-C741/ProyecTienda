<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tenants', function (Blueprint $table) {
            // Colores personalizados
            $table->string('primary_color')->default('#007bff')->after('banner');
            $table->string('secondary_color')->default('#6c757d')->after('primary_color');
            $table->string('accent_color')->default('#28a745')->after('secondary_color');
            
            // Tipografía
            $table->string('font_family')->default('Inter')->after('accent_color');
            
            // Layout y diseño
            $table->enum('product_layout', ['grid', 'list', 'masonry'])->default('grid')->after('font_family');
            $table->integer('products_per_page')->default(24)->after('product_layout');
            
            // Banner y hero
            $table->text('hero_text')->nullable()->after('products_per_page');
            $table->string('hero_button_text')->nullable()->after('hero_text');
            $table->string('hero_button_link')->nullable()->after('hero_button_text');
            
            // Redes sociales
            $table->string('facebook_url')->nullable()->after('phone');
            $table->string('instagram_url')->nullable()->after('facebook_url');
            $table->string('twitter_url')->nullable()->after('instagram_url');
            $table->string('whatsapp_number')->nullable()->after('twitter_url');
            
            // SEO
            $table->string('meta_title')->nullable()->after('description');
            $table->text('meta_description')->nullable()->after('meta_title');
            $table->text('meta_keywords')->nullable()->after('meta_description');
        });
    }

    public function down(): void
    {
        Schema::table('tenants', function (Blueprint $table) {
            $table->dropColumn([
                'primary_color',
                'secondary_color',
                'accent_color',
                'font_family',
                'product_layout',
                'products_per_page',
                'hero_text',
                'hero_button_text',
                'hero_button_link',
                'facebook_url',
                'instagram_url',
                'twitter_url',
                'whatsapp_number',
                'meta_title',
                'meta_description',
                'meta_keywords'
            ]);
        });
    }
};
