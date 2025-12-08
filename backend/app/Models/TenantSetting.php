<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TenantSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'tenant_id',
        'primary_color',
        'secondary_color',
        'font_family',
        'custom_css',
        'show_categories',
        'show_search',
        'show_reviews',
        'social_links',
    ];

    protected $casts = [
        'show_categories' => 'boolean',
        'show_search' => 'boolean',
        'show_reviews' => 'boolean',
        'social_links' => 'array',
    ];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }
}
