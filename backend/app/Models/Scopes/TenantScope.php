<?php

namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\Auth;

/**
 * TenantScope
 * 
 * Global Scope que filtra AUTOMÁTICAMENTE todos los queries por tenant_id
 * cuando el usuario autenticado es un tenant_admin
 * 
 * SEGURIDAD: Garantiza que un vendedor NUNCA vea datos de otro vendedor
 */
class TenantScope implements Scope
{
    /**
     * Aplicar el scope al query builder
     */
    public function apply(Builder $builder, Model $model): void
    {
        $user = Auth::user();
        
        // Si el usuario es un tenant_admin, filtrar por su tenant_id
        if ($user && $user->role === 'tenant_admin' && $user->tenant_id) {
            $builder->where($model->getTable() . '.tenant_id', $user->tenant_id);
        }
        
        // Los super_admin ven todo (no se aplica filtro)
        // Los customers solo verán productos del storefront donde estén (filtrado en controlador)
    }
}
