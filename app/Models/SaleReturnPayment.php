<?php

declare(strict_types=1);

namespace App\Models;

use App\Support\HasAdvancedFilter;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\SoftDeletes;

class SaleReturnPayment extends Model
{
    use HasAdvancedFilter;
    use SoftDeletes;

    public const ATTRIBUTES = [
        'id',
        'sale_return_id',
        'amount',
        'payment_method',
        'created_at',
        'updated_at',

    ];

    public $orderable = self::ATTRIBUTES;
    public $filterable = self::ATTRIBUTES;
    protected $guarded = [];

    /** @return \Illuminate\Database\Eloquent\Relations\BelongsTo */
    public function saleReturn(): BelongsTo
    {
        return $this->belongsTo(SaleReturn::class, 'sale_return_id', 'id');
    }

    /**
     * Get ajustement date.
     *
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
    public function date(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => Carbon::parse($value)->format('d M, Y'),
        );
    }

    /**
     * @param mixed $query
     *
     * @return mixed
     */
    public function scopeBySaleReturn($query)
    {
        return $query->whereSaleReturnId(request()->route('sale_return_id'));
    }
}
