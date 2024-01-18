<?php

declare(strict_types=1);

namespace App\Models;

use App\Support\HasAdvancedFilter;
use App\Traits\GetModelByUuid;
use App\Traits\UuidGenerator;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;
use App\Enums\ServiceType;
use App\Enums\BloodType;
use Carbon\Carbon;

class Customer extends Model
{
    use HasAdvancedFilter;
    use GetModelByUuid;
    use UuidGenerator;
    use HasFactory;
    use SoftDeletes;

    public const ATTRIBUTES = [
        'id',
        'name',
        'email',
        'phone',
        'city',
        'country',
        'address',
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
        'uuid',
        'id',
        'city',
        'tax_number',
        'name',
        'email',
        'phone',
        'country',
        'address',
        'blood_type',
    ];

    /** @return HasOne<Wallet> */
    public function wallet(): HasOne
    {
        return $this->hasOne(Wallet::class);
    }

    /** @return HasOne<Sale> */
    public function sales(): HasOne
    {
        return $this->HasOne(Sale::class);
    }

    public function getTotalSalesAttribute()
    {
        return $this->customerSum('total_amount', Sale::class);
    }

    public function getTotalSaleReturnsAttribute(): int|float
    {
        return $this->customerSum('total_amount', SaleReturn::class);
    }

    public function getTotalPaymentsAttribute(): int|float
    {
        return $this->customerSum('paid_amount', Sale::class);
    }

    public function getTotalDueAttribute(): int|float
    {
        return $this->customerSum('due_amount', Sale::class);
    }

    public function getProfit(): int|float
    {
        $sales = Sale::where('customer_id', $this->id)
            ->completed()->sum('total_amount');

        $sale_returns = SaleReturn::where('customer_id', $this->id)
            ->completed()->sum('total_amount');

        $product_costs = 0;

        foreach (Sale::where('customer_id', $this->id)->with('saleDetails', 'saleDetails.product', 'saleDetailsService.service')->get() as $sale) {
            foreach ($sale->saleDetails as $saleDetail) {
                $product_costs += $saleDetail->product->cost;
            }
        }

        $revenue = ($sales - $sale_returns);

        return $revenue - $product_costs;
    }

    private function customerSum($column, $model)
    {
        return $model::where('customer_id', $this->id)->sum($column);
    }

    /**
     * 
     */

    public function lastInscription(): HasOne
    {
        return $this->hasOne(SaleDetailsService::class)->ofMany([
            'created_at' => 'max',
            'id' => 'max',
        ], function (Builder $query) {
            $query->where('created_at', '<', now())->where('service_id', ServiceType::INSCRIPTION->getID());
        });
    }

    public function getInscriptionDateDiffForHumansAttribute()
    {
        return $this->lastInscription->created_at->diffForHumans();
    }

    public function getInscriptionExpirationAttribute()
    {
        return $this->lastInscription->created_at->addYear();
    }

    public function getInscriptionRemainingAttribute()
    {
        if ($this->inscription_expiration) {
            $remaining_days = now()->diffInDays($this->inscription_expiration, false);
        } else {
            $remaining_days = 0;
        }

        return $remaining_days;
    }

    public function getInscriptionDateAttribute()
    {
        return $this->lastInscription ?? '';
    }
}
