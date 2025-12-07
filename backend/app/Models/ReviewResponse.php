<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReviewResponse extends Model
{
    use HasFactory;

    protected $fillable = [
        'review_id',
        'user_id',
        'response',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    /**
     * Relación con la reseña
     */
    public function review()
    {
        return $this->belongsTo(ProductReview::class, 'review_id');
    }

    /**
     * Relación con el usuario (vendedor)
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
