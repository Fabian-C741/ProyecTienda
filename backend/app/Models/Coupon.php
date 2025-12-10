<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Scopes\TenantScope;
use Carbon\Carbon;

class Coupon extends Model
{
    use HasFactory;

    protected $fillable = [
        'tenant_id',
        'code',
        'type',
        'value',
        'min_purchase',
        'max_uses',
        'used_count',
        'expires_at',
        'is_active',
        'description'
    ];

    /**
     * CRÍTICO: Aplicar Global Scope para aislamiento de cupones por tenant
     */
    protected static function booted()
    {
        static::addGlobalScope(new TenantScope());

        // Auto-asignar tenant_id al crear cupón
        static::creating(function ($coupon) {
            if (auth()->check() && auth()->user()->tenant_id) {
                $coupon->tenant_id = auth()->user()->tenant_id;
            }
        });
    }

    /**
     * Relación con Tenant
     */
    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    protected $casts = [
        'expires_at' => 'datetime',
        'is_active' => 'boolean',
        'value' => 'decimal:2',
        'min_purchase' => 'decimal:2'
    ];

    public function isValid($orderTotal = 0)
    {
        // Verificar si está activo
        if (!$this->is_active) {
            return false;
        }

        // Verificar fecha de expiración
        if ($this->expires_at && Carbon::now()->gt($this->expires_at)) {
            return false;
        }

        // Verificar usos máximos
        if ($this->max_uses && $this->used_count >= $this->max_uses) {
            return false;
        }

        // Verificar compra mínima
        if ($this->min_purchase && $orderTotal < $this->min_purchase) {
            return false;
        }

        return true;
    }

    public function calculateDiscount($orderTotal)
    {
        if (!$this->isValid($orderTotal)) {
            return 0;
        }

        if ($this->type === 'percentage') {
            return ($orderTotal * $this->value) / 100;
        }

        return min($this->value, $orderTotal);
    }

    public function incrementUsage()
    {
        $this->increment('used_count');
    }
}
