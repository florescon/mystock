<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\PaymentStatus;
use App\Enums\PurchaseStatus;
use App\Support\HasAdvancedFilter;
use App\Traits\GetModelByUuid;
use App\Traits\UuidGenerator;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Purchase extends Model
{
    use HasAdvancedFilter;
    use GetModelByUuid;
    use UuidGenerator;
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
        'created_at',
        'updated_at',
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
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'status'         => PurchaseStatus::class,
        'payment_status' => PaymentStatus::class,
    ];

    public function purchaseDetails(): HasMany
    {
        return $this->hasMany(PurchaseDetail::class, 'purchase_id', 'id');
    }

    public function purchasePayments(): HasMany
    {
        return $this->hasMany(PurchasePayment::class, 'purchase_id', 'id');
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(
            related: Supplier::class,
            foreignKey: 'supplier_id',
        );
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(
            related: User::class,
            foreignKey: 'user_id',
        );
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($purchase) {
            $prefix = settings()->purchase_prefix;

            $latestPurchase = self::latest()->first();

            if ($latestPurchase) {
                $number = intval(substr($latestPurchase->reference, -3)) + 1;
            } else {
                $number = 1;
            }

            $purchase->reference = $prefix.str_pad(strval($number), 3, '0', STR_PAD_LEFT);
        });
    }

    /** @param mixed $query */
    public function scopeCompleted($query)
    {
        return $query->whereStatus(2);
    }

    public function scopeThisMonth($query)
    {
        return $query->whereMonth('date', now()->month);
    }

}
