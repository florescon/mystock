<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\PaymentStatus;
use App\Enums\SaleStatus;
use App\Scopes\SaleScope;
use App\Support\HasAdvancedFilter;
use App\Traits\GetModelByUuid;
use App\Traits\UuidGenerator;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Sale extends Model
{
    use HasAdvancedFilter;
    use SaleScope;
    use UuidGenerator;
    use GetModelByUuid;
    use SoftDeletes;

    public const ATTRIBUTES = [
        'id',
        'reference',
        'customer_id',
        'warehouse_id',
        'tax_percentage',
        'tax_amount',
        'discount_percentage',
        'discount_amount',
        'shipping_amount',
        'total_amount',
        'payment_date',
        'paid_amount',
        'due_amount',
        'status',
        'payment_status',
        'payment_method',
        'shipping_status',
        'created_at',
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
        'uuid',
        'date',
        'reference',
        'customer_id',
        'user_id',
        'warehouse_id',
        'tax_percentage',
        'tax_amount',
        'payment_date',
        'discount_percentage',
        'discount_amount',
        'shipping_amount',
        'total_amount',
        'paid_amount',
        'due_amount',
        'status',
        'payment_status',
        'payment_method',
        'shipping_status',
        'note',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'status'         => SaleStatus::class,
        'payment_status' => PaymentStatus::class,
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($sale) {
            $prefix = settings()->sale_prefix;

            $latestSale = self::latest()->first();

            if ($latestSale) {
                $number = intval(substr($latestSale->reference, -3)) + 1;
            } else {
                $number = 1;
            }

            $sale->reference = $prefix.str_pad(strval($number), 3, '0', STR_PAD_LEFT);
        });
    }

    public function saleDetails(): HasMany
    {
        return $this->hasMany(SaleDetails::class);
    }

    public function saleDetailsService(): HasMany
    {
        return $this->hasMany(SaleDetailsService::class);
    }

    public function salePayments(): HasMany
    {
        return $this->hasMany(SalePayment::class, 'sale_id', 'id');
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(
            related: Customer::class,
            foreignKey: 'customer_id',
        );
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(
            related: User::class,
            foreignKey: 'user_id',
        );
    }

    /**
     * @param mixed $query
     *
     * @return mixed
     */
    public function scopeCompleted($query)
    {
        return $query->whereStatus(2);
    }

    public function scopeThisMonth($query)
    {
        return $query->whereMonth('date', now()->month);
    }

}
