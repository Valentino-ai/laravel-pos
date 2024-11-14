<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'description',
        'size_id',
        'unit_price',
        'category_id',
        'color',
        'material_id',
        'image_url',
    ];

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * The "type" of the primary key ID.
     *
     * @var string
     */
    protected $keyType = 'string';

    /**
     * Relationships.
     */

    // Relationship to the Size model
    public function size()
    {
        return $this->belongsTo(Size::class);
    }

    // Relationship to the Category model
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // Relationship to the Material model
    public function material()
    {
        return $this->belongsTo(Material::class);
    }
}