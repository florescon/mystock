<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\PaymentStatus;
use App\Enums\PurchaseReturnStatus;
use App\Support\HasAdvancedFilter;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class PurchaseReturn extends Model
{
    use HasAdvancedFilter;
    use SoftDeletes;

    public const ATTRIBUTES = [
        'id',
        'date',
        'reference',
        'supplier_id',
        'tax_percentage',
        'tax_amount',
        'discount_percentage',
        'discount_amount',
        'shipping_amount',
        'total_amount',
        'paid_amount',
        'due_amount',
        'status',
        'payment_status',
        'payment_method',
        'supplier_id',
    ];
    public $orderable = self::ATTRIBUTES;
    public $filterable = self::ATTRIBUTES;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'date',
        'reference',
        'supplier_id',
        'user_id',
        'warehouse_id',
        'tax_percentage',
        'tax_amount',
        'discount_percentage',
        'discount_amount',
        'shipping_amount',
        'total_amount',
        'paid_amount',
        'due_amount',
        'status',
        'payment_status',
        'payment_method',
        'note',
        'supplier_id',
    ];

    protected $casts = [
        'status'         => PurchaseReturnStatus::class,
        'payment_status' => PaymentStatus::class,
    ];

    /** @return HasMany<PurchaseReturnDetail> */
    public function purchaseReturnDetails(): HasMany
    {
        return $this->hasMany(PurchaseReturnDetail::class, 'purchase_return_id', 'id');
    }

    public function purchaseReturnPayments(): HasMany
    {
        return $this->hasMany(PurchaseReturnPayment::class, 'purchase_return_id', 'id');
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(
            related: Supplier::class,
            foreignKey: 'user_id',
        );
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($purchaseReturn) {
            $prefix = settings()->purchaseReturn_prefix;

            $latestPurchaseReturn = self::latest()->first();

            if ($latestPurchaseReturn) {
                $number = intval(substr($latestPurchaseReturn->reference, -3)) + 1;
            } else {
                $number = 1;
            }

            $purchaseReturn->reference = $prefix.str_pad(strval($number), 3, '0', STR_PAD_LEFT);
        });
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

}
