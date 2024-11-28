<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShippingAddress extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'phone_number',
        'street',
        'company',
        'city',
        'postal_code',
        'country_code',
        'province_code',
        'province_name',
        'state_province_region',
        'latitude',
        'longitude',
        'space_station',
        'planet_or_moon',
        'star_system',
        'sector',
        'quadrant',
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
    ];
}
