<?php

namespace App\Models;

use App\Enums\OrderPriority;
use App\Enums\OrderStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/** @use HasFactory<\Database\Factories\OrderFactory> */
class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'stripe_payment_intent_id',
        'status',
        'priority',
        'total',
    ];

    protected function casts(): array
    {
        return [
            'total' => 'decimal:2',
            'status' => OrderStatus::class,
            'priority' => OrderPriority::class,
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function scopeByStatus(Builder $query, OrderStatus $status): Builder
    {
        return $query->where('status', $status);
    }

    public function scopeByPriority(Builder $query, OrderPriority $priority): Builder
    {
        return $query->where('priority', $priority);
    }

    public function scopeRecent(Builder $query): Builder
    {
        return $query->latest();
    }
}
