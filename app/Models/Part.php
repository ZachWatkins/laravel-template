<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Part extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'status',
        'number',
        'name',
        'sku',
        'inventory',
        'price',
        'weight',
        'weight_unit',
        'filename',
        'published_at',
        'part_type_id',
        'manufacturer_id',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'price' => 'decimal:2',
        'weight' => 'decimal:2',
        'published_at' => 'timestamp',
        'part_type_id' => 'integer',
        'manufacturer_id' => 'integer',
    ];

    public function partType(): BelongsTo
    {
        return $this->belongsTo(PartType::class);
    }

    public function manufacturer(): BelongsTo
    {
        return $this->belongsTo(Manufacturer::class);
    }

    public function partWebpage(): HasOne
    {
        return $this->hasOne(PartWebpage::class);
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function partPhotos(): HasMany
    {
        return $this->hasMany(PartPhoto::class);
    }

    public function monitors(): HasMany
    {
        return $this->hasMany(Monitor::class);
    }
}
