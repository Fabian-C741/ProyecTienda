<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Coupon extends Model
{
    use HasFactory;

    protected $fillable = [
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
