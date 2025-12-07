<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'tenant_id',
        'category_id',
        'name',
        'slug',
        'sku',
        'description',
        'short_description',
        'price',
        'compare_price',
        'cost',
        'stock',
        'low_stock_threshold',
        'track_inventory',
        'allow_backorder',
        'images',
        'featured_image',
        'variants',
        'attributes',
        'weight',
        'weight_unit',
        'dimensions',
        'is_featured',
        'is_active',
        'published_at',
        'views_count',
    ];

    protected $casts = [
        'images' => 'array',
        'variants' => 'array',
        'attributes' => 'array',
        'dimensions' => 'array',
        'price' => 'decimal:2',
        'compare_price' => 'decimal:2',
        'cost' => 'decimal:2',
        'weight' => 'float',
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
        'track_inventory' => 'boolean',
        'allow_backorder' => 'boolean',
        'published_at' => 'datetime',
    ];

    protected $appends = ['average_rating', 'total_reviews'];

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(ProductReview::class);
    }

    public function approvedReviews(): HasMany
    {
        return $this->hasMany(ProductReview::class)->where('is_approved', true);
    }

    // Accessor para rating promedio
    public function getAverageRatingAttribute()
    {
        return $this->approvedReviews()->avg('rating') ?? 0;
    }

    // Accessor para total de reviews
    public function getTotalReviewsAttribute()
    {
        return $this->approvedReviews()->count();
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true)
                    ->whereNotNull('published_at')
                    ->where('published_at', '<=', now());
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeInStock($query)
    {
        return $query->where(function($q) {
            $q->where('track_inventory', false)
              ->orWhere('stock', '>', 0)
              ->orWhere('allow_backorder', true);
        });
    }

    public function isInStock(): bool
    {
        if (!$this->track_inventory) {
            return true;
        }
        
        return $this->stock > 0 || $this->allow_backorder;
    }

    public function isLowStock(): bool
    {
        return $this->track_inventory && $this->stock <= $this->low_stock_threshold;
    }

    public function incrementViews(): void
    {
        $this->increment('views_count');
    }
}
