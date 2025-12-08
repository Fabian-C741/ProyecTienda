<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Tabla de tenants (vendedores/tiendas)
        Schema::create('tenants', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Nombre de la tienda
            $table->string('slug')->unique(); // URL amigable: mitienda.com/tienda/slug
            $table->string('domain')->nullable(); // Dominio personalizado opcional
            $table->string('logo')->nullable();
            $table->string('banner')->nullable();
            $table->text('description')->nullable();
            $table->string('email');
            $table->string('phone')->nullable();
            $table->string('address')->nullable();
            $table->enum('status', ['active', 'suspended', 'inactive'])->default('active');
            $table->decimal('commission_rate', 5, 2)->default(10.00); // Comisión del 10%
            $table->timestamps();
            $table->softDeletes();
        });

        // Configuración personalizada de cada tienda
        Schema::create('tenant_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->onDelete('cascade');
            $table->string('primary_color')->default('#2563eb');
            $table->string('secondary_color')->default('#1e40af');
            $table->string('font_family')->default('Inter');
            $table->text('custom_css')->nullable();
            $table->boolean('show_categories')->default(true);
            $table->boolean('show_search')->default(true);
            $table->boolean('show_reviews')->default(true);
            $table->json('social_links')->nullable(); // Facebook, Instagram, etc.
            $table->timestamps();
        });

        // Pasarelas de pago por tenant
        Schema::create('tenant_payment_gateways', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->onDelete('cascade');
            $table->enum('gateway', ['mercadopago', 'stripe', 'paypal', 'bank_transfer']);
            $table->boolean('is_active')->default(false);
            $table->text('public_key')->nullable();
            $table->text('access_token')->nullable(); // Encriptado
            $table->text('client_id')->nullable();
            $table->text('client_secret')->nullable();
            $table->json('config')->nullable(); // Configuración adicional
            $table->timestamps();
        });

        // Agregar tenant_id a users
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('tenant_id')->nullable()->after('id')->constrained()->onDelete('cascade');
            $table->enum('role', ['super_admin', 'tenant_admin', 'customer'])->default('customer')->after('password');
        });

        // Agregar tenant_id a products
        Schema::table('products', function (Blueprint $table) {
            $table->foreignId('tenant_id')->nullable()->after('id')->constrained()->onDelete('cascade');
        });

        // Agregar tenant_id a categories
        Schema::table('categories', function (Blueprint $table) {
            $table->foreignId('tenant_id')->nullable()->after('id')->constrained()->onDelete('cascade');
        });

        // Agregar tenant_id a orders
        Schema::table('orders', function (Blueprint $table) {
            $table->foreignId('tenant_id')->nullable()->after('id')->constrained()->onDelete('cascade');
        });

        // Agregar tenant_id a coupons
        Schema::table('coupons', function (Blueprint $table) {
            $table->foreignId('tenant_id')->nullable()->after('id')->constrained()->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('coupons', function (Blueprint $table) {
            $table->dropForeign(['tenant_id']);
            $table->dropColumn('tenant_id');
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['tenant_id']);
            $table->dropColumn('tenant_id');
        });

        Schema::table('categories', function (Blueprint $table) {
            $table->dropForeign(['tenant_id']);
            $table->dropColumn('tenant_id');
        });

        Schema::table('products', function (Blueprint $table) {
            $table->dropForeign(['tenant_id']);
            $table->dropColumn('tenant_id');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['tenant_id']);
            $table->dropColumn(['tenant_id', 'role']);
        });

        Schema::dropIfExists('tenant_payment_gateways');
        Schema::dropIfExists('tenant_settings');
        Schema::dropIfExists('tenants');
    }
};
