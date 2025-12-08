<?php

namespace App\Observers;

use App\Models\Tenant;
use App\Services\CpanelService;
use Illuminate\Support\Facades\Log;

class TenantObserver
{
    private $cpanel;

    public function __construct(CpanelService $cpanel)
    {
        $this->cpanel = $cpanel;
    }

    /**
     * Handle the Tenant "created" event.
     */
    public function created(Tenant $tenant): void
    {
        // Solo crear subdominio si está habilitada la integración con cPanel
        if (!config('services.cpanel.username') || !config('services.cpanel.api_token')) {
            Log::warning("Integración cPanel no configurada. Subdominio no creado para: {$tenant->slug}");
            return;
        }

        try {
            $rootDomain = config('services.cpanel.root_domain');
            
            Log::info("Creando subdominio para tenant: {$tenant->slug}");
            
            $result = $this->cpanel->createSubdomain($tenant->slug, $rootDomain);
            
            if ($result['success']) {
                $tenant->update([
                    'subdomain_created' => true,
                    'subdomain_created_at' => now(),
                ]);
                
                Log::info("Subdominio creado exitosamente: {$tenant->slug}.{$rootDomain}");
            } else {
                Log::error("Error al crear subdominio para {$tenant->slug}: " . ($result['message'] ?? 'Unknown error'));
                
                // Marcar que falló para reintento manual
                $tenant->update(['subdomain_created' => false]);
            }
            
        } catch (\Exception $e) {
            Log::error("Excepción al crear subdominio para {$tenant->slug}: " . $e->getMessage());
            $tenant->update(['subdomain_created' => false]);
        }
    }

    /**
     * Handle the Tenant "updated" event.
     */
    public function updated(Tenant $tenant): void
    {
        // Si cambia el slug, actualizar el subdominio
        if ($tenant->isDirty('slug') && $tenant->subdomain_created) {
            Log::info("Slug cambiado de {$tenant->getOriginal('slug')} a {$tenant->slug}");
            
            // Eliminar subdominio antiguo
            $oldSlug = $tenant->getOriginal('slug');
            $rootDomain = config('services.cpanel.root_domain');
            
            $this->cpanel->deleteSubdomain($oldSlug, $rootDomain);
            
            // Crear nuevo subdominio
            $result = $this->cpanel->createSubdomain($tenant->slug, $rootDomain);
            
            if (!$result['success']) {
                Log::error("Error al recrear subdominio después de cambio de slug");
            }
        }
    }

    /**
     * Handle the Tenant "deleted" event.
     */
    public function deleted(Tenant $tenant): void
    {
        // Eliminar subdominio cuando se elimina el tenant
        if ($tenant->subdomain_created) {
            $rootDomain = config('services.cpanel.root_domain');
            
            Log::info("Eliminando subdominio: {$tenant->slug}.{$rootDomain}");
            
            $this->cpanel->deleteSubdomain($tenant->slug, $rootDomain);
        }
    }
}
