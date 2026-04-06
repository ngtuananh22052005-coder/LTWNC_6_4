<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Product extends Model
{
    protected $table = '23810310109_products';

    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'description',
        'price',
        'stock_quantity',
        'image_path',
        'status',
        'discount_percent',
    ];

    protected $casts = [
        'price'            => 'integer',
        'stock_quantity'   => 'integer',
        'discount_percent' => 'integer',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function getFinalPriceAttribute(): int
    {
        return (int) ($this->price * (1 - $this->discount_percent / 100));
    }
}
