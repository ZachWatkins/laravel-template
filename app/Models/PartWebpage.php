<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PartWebpage extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'status',
        'path',
        'title',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'content',
        'part_id',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'part_id' => 'integer',
    ];

    public function part(): BelongsTo
    {
        return $this->belongsTo(Part::class);
    }
}
