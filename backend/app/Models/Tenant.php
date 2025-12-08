<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Tenant extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'domain',
        'logo',
        'banner',
        'description',
        'email',
        'phone',
        'address',
        'status',
        'commission_rate',
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
        'meta_keywords',
    ];

    protected $casts = [
        'commission_rate' => 'decimal:2',
    ];

    // Relaciones
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function admin()
    {
        return $this->hasOne(User::class)->where('role', 'tenant_admin');
    }

    public function customers()
    {
        return $this->hasMany(User::class)->where('role', 'customer');
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function categories(): HasMany
    {
        return $this->hasMany(Category::class);
    }

    public function settings(): HasOne
    {
        return $this->hasOne(TenantSetting::class);
    }

    public function paymentGateways(): HasMany
    {
        return $this->hasMany(TenantPaymentGateway::class);
    }

    public function pages(): HasMany
    {
        return $this->hasMany(TenantPage::class);
    }

    public function coupons(): HasMany
    {
        return $this->hasMany(Coupon::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    // Atributos computados
    public function getUrlAttribute()
    {
        if ($this->domain) {
            return 'https://' . $this->domain;
        }
        return url('/shop/' . $this->slug);
    }

    public function getTotalSalesAttribute()
    {
        return $this->orders()
            ->whereIn('status', ['delivered'])
            ->sum('total');
    }

    public function getCommissionAmountAttribute()
    {
        return ($this->total_sales * $this->commission_rate) / 100;
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
