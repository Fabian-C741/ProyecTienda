<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

class TenantPaymentGateway extends Model
{
    use HasFactory;

    protected $fillable = [
        'tenant_id',
        'gateway',
        'is_active',
        'public_key',
        'access_token',
        'client_id',
        'client_secret',
        'config',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'config' => 'array',
    ];

    protected $hidden = [
        'access_token',
        'client_secret',
    ];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    // Encriptar tokens automáticamente
    public function setAccessTokenAttribute($value)
    {
        $this->attributes['access_token'] = $value ? Crypt::encryptString($value) : null;
    }

    public function getAccessTokenAttribute($value)
    {
        return $value ? Crypt::decryptString($value) : null;
    }

    public function setClientSecretAttribute($value)
    {
        $this->attributes['client_secret'] = $value ? Crypt::encryptString($value) : null;
    }

    public function getClientSecretAttribute($value)
    {
        return $value ? Crypt::decryptString($value) : null;
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeMercadoPago($query)
    {
        return $query->where('gateway', 'mercadopago');
    }
}
