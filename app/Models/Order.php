<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Order extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'number',
        'status',
        'total',
        'notes',
        'customer_id',
        'customer_payment_method_id',
        'customer_shipping_address_id',
        'customer_billing_address_id',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'total' => 'decimal:2',
        'customer_id' => 'integer',
        'customer_payment_method_id' => 'integer',
        'customer_shipping_address_id' => 'integer',
        'customer_billing_address_id' => 'integer',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function customerPaymentMethod(): BelongsTo
    {
        return $this->belongsTo(CustomerPaymentMethod::class);
    }

    public function customerShippingAddress(): BelongsTo
    {
        return $this->belongsTo(CustomerShippingAddress::class);
    }

    public function customerBillingAddress(): BelongsTo
    {
        return $this->belongsTo(CustomerBillingAddress::class);
    }

    public function shoppingCart(): HasOne
    {
        return $this->hasOne(ShoppingCart::class);
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }
}
