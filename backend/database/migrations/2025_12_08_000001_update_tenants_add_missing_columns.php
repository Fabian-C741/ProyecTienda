<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Agregar columnas faltantes a la tabla tenants existente
        Schema::table('tenants', function (Blueprint $table) {
            if (!Schema::hasColumn('tenants', 'logo')) {
                $table->string('logo')->nullable()->after('slug');
            }
            if (!Schema::hasColumn('tenants', 'banner')) {
                $table->string('banner')->nullable()->after('logo');
            }
            if (!Schema::hasColumn('tenants', 'description')) {
                $table->text('description')->nullable()->after('banner');
            }
            if (!Schema::hasColumn('tenants', 'phone')) {
                $table->string('phone')->nullable()->after('email');
            }
            if (!Schema::hasColumn('tenants', 'address')) {
                $table->string('address')->nullable()->after('phone');
            }
            if (!Schema::hasColumn('tenants', 'status')) {
                $table->enum('status', ['active', 'suspended', 'inactive'])->default('active')->after('address');
            }
            if (!Schema::hasColumn('tenants', 'commission_rate')) {
                $table->decimal('commission_rate', 5, 2)->default(10.00)->after('status');
            }
            if (!Schema::hasColumn('tenants', 'deleted_at')) {
                $table->softDeletes();
            }
        });

        // Crear tabla tenant_settings si no existe
        if (!Schema::hasTable('tenant_settings')) {
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
                $table->json('social_links')->nullable();
                $table->timestamps();
            });
        }

        // Crear tabla tenant_payment_gateways si no existe
        if (!Schema::hasTable('tenant_payment_gateways')) {
            Schema::create('tenant_payment_gateways', function (Blueprint $table) {
                $table->id();
                $table->foreignId('tenant_id')->constrained()->onDelete('cascade');
                $table->string('gateway_name'); // mercadopago, stripe, paypal, etc.
                $table->boolean('is_active')->default(false);
                $table->string('public_key')->nullable();
                $table->text('access_token')->nullable(); // Encriptado
                $table->string('client_id')->nullable();
                $table->text('client_secret')->nullable();
                $table->json('config')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('tenant_payment_gateways');
        Schema::dropIfExists('tenant_settings');
        
        Schema::table('tenants', function (Blueprint $table) {
            $table->dropColumn([
                'logo', 'banner', 'description', 'phone', 'address', 
                'status', 'commission_rate', 'deleted_at'
            ]);
        });
    }
};
