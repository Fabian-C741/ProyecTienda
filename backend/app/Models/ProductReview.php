<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductReview extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'product_id',
        'user_id',
        'order_id',
        'rating',
        'title',
        'comment',
        'images',
        'is_verified_purchase',
        'is_approved',
        'approved_at',
        'approved_by',
    ];

    protected $casts = [
        'images' => 'array',
        'is_verified_purchase' => 'boolean',
        'is_approved' => 'boolean',
        'approved_at' => 'datetime',
        'created_at' => 'datetime',
    ];

    protected $appends = ['helpful_count'];

    /**
     * Relación con el producto
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Relación con el usuario
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relación con la orden
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Relación con el usuario que aprobó
     */
    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * Respuestas del vendedor
     */
    public function responses()
    {
        return $this->hasMany(ReviewResponse::class, 'review_id');
    }

    /**
     * Contar votos útiles (simulado)
     */
    public function getHelpfulCountAttribute()
    {
        return 0; // Se puede implementar con otra tabla si se desea
    }

    /**
     * Scope para reseñas aprobadas
     */
    public function scopeApproved($query)
    {
        return $query->where('is_approved', true);
    }

    /**
     * Scope para reseñas de compras verificadas
     */
    public function scopeVerified($query)
    {
        return $query->where('is_verified_purchase', true);
    }

    /**
     * Scope por calificación
     */
    public function scopeRating($query, $rating)
    {
        return $query->where('rating', $rating);
    }

    /**
     * Calcular promedio de calificaciones de un producto
     */
    public static function averageRating($productId)
    {
        return static::where('product_id', $productId)
            ->approved()
            ->avg('rating');
    }

    /**
     * Contar reseñas por calificación
     */
    public static function ratingDistribution($productId)
    {
        $distribution = static::where('product_id', $productId)
            ->approved()
            ->selectRaw('rating, COUNT(*) as count')
            ->groupBy('rating')
            ->pluck('count', 'rating')
            ->toArray();

        // Asegurar que todas las calificaciones 1-5 existen
        return [
            5 => $distribution[5] ?? 0,
            4 => $distribution[4] ?? 0,
            3 => $distribution[3] ?? 0,
            2 => $distribution[2] ?? 0,
            1 => $distribution[1] ?? 0,
        ];
    }
}
