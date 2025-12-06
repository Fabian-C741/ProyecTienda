<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PaymentGateway extends Model
{
    use HasFactory;

    protected $fillable = [
        'tenant_id',
        'name',
        'display_name',
        'credentials',
        'settings',
        'is_active',
        'is_test_mode',
    ];

    protected $casts = [
        'credentials' => 'array',
        'settings' => 'array',
        'is_active' => 'boolean',
        'is_test_mode' => 'boolean',
    ];

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
