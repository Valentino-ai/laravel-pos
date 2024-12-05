<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Support\Str;

class Checkout extends Model
{
    use HasFactory, HasUuids;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'checkout';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * The "type" of the primary key ID.
     *
     * @var string
     */
    protected $keyType = 'string';

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'products',
        'customer_name',
        'code',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'products' => 'array', // Automatically casts JSON to array
    ];

    /**
     * Generate a unique code for the checkout.
     *
     * @return string
     */
    public static function generateUniqueCode()
    {
        $datePrefix = now()->format('Ymd');
        $existingCodes = static::where('code', 'LIKE', "$datePrefix%")->pluck('code');

        $nextNumber = $existingCodes
            ->map(fn($code) => (int) Str::afterLast($code, '_'))
            ->max() + 1;

        return "{$datePrefix}_{$nextNumber}";
    }
}
