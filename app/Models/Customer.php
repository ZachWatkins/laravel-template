<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Customer extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'birthday',
        'phone_number',
        'subscribed_to_newsletter',
        'user_id',
        'shipping_address_id',
        'billing_address_id',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'birthday' => 'date',
        'subscribed_to_newsletter' => 'boolean',
        'user_id' => 'integer',
        'shipping_address_id' => 'integer',
        'billing_address_id' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function shippingAddress(): BelongsTo
    {
        return $this->belongsTo(ShippingAddress::class);
    }

    public function billingAddress(): BelongsTo
    {
        return $this->belongsTo(BillingAddress::class);
    }

    public function shoppingCart(): HasOne
    {
        return $this->hasOne(ShoppingCart::class);
    }

    public function customerPaymentMethod(): HasOne
    {
        return $this->hasOne(CustomerPaymentMethod::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }
}
