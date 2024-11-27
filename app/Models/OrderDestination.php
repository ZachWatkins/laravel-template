<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderDestination extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'address_line_1',
        'address_line_2',
        'city',
        'state_province_region',
        'postal_code',
        'country_code',
        'latitude',
        'longitude',
        'space_station',
        'planet_or_moon',
        'star_system',
        'sector',
        'quadrant',
        'order_payment_id',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'order_payment_id' => 'integer',
    ];

    public function orderPayment(): BelongsTo
    {
        return $this->belongsTo(OrderPayment::class);
    }
}
