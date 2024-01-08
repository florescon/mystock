<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Support\HasAdvancedFilter;
use Illuminate\Database\Eloquent\SoftDeletes;

class SaleDetailsService extends Model
{
    use HasAdvancedFilter;
    use SoftDeletes;

    public const ATTRIBUTES = [
        'id',
        'name',
    ];

    public $orderable = self::ATTRIBUTES;
    public $filterable = self::ATTRIBUTES;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id',
        'sale_id',
        'service_id',
        'customer_id',
        'name',
        'code',
        'quantity',
        'price',
        'unit_price',
        'sub_total',
        'product_discount_amount',
        'product_discount_type',
        'product_tax_amount',
        'with_days',
    ];

    public function setWithDaysAttribute($value)
    {
        $this->attributes['with_days'] = implode(',', $value ?? '');
    }

    public function getWithDaysAttribute($value)
    {
        return explode(',',(string) $value ?? '');
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(
            related: Customer::class,
            foreignKey: 'customer_id',
        );
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class, 'service_id', 'id');
    }

    public function sale(): BelongsTo
    {
        return $this->belongsTo(Sale::class, 'sale_id', 'id');
    }

}
