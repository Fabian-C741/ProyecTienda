<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Scopes\TenantScope;

class Category extends Model
{
    use HasFactory;

    /**
     * The "booted" method of the model.
     * Aplica TenantScope automáticamente para PROTEGER categorías entre tenants
     */
    protected static function booted(): void
    {
        static::addGlobalScope(new TenantScope());
        
        // Auto-asignar tenant_id al crear una categoría si el usuario es tenant_admin
        static::creating(function (Category $category) {
            if (auth()->check() && auth()->user()->role === 'tenant_admin' && auth()->user()->tenant_id) {
                $category->tenant_id = auth()->user()->tenant_id;
            }
        });
    }

    protected $fillable = [
        'tenant_id',
        'name',
        'slug',
        'description',
        'image',
        'parent_id',
        'order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(Category::class, 'parent_id')->orderBy('order');
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeRoot($query)
    {
        return $query->whereNull('parent_id');
    }
}
