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
        'phone',
        'shipping_street_1',
        'shipping_street_2',
        'shipping_city',
        'shipping_state',
        'shipping_zip_code',
        'shipping_instructions',
        'billing_street_1',
        'billing_street_2',
        'billing_city',
        'billing_state',
        'billing_zip_code',
        'billing_card_name',
        'billing_card_number',
        'billing_card_expiration',
        'billing_card_cvv',
        'user_id',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'user_id' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function shoppingCart(): HasOne
    {
        return $this->hasOne(Order::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }
}
