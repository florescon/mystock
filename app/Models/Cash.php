<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Support\HasAdvancedFilter;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Cash extends Model
{
    use HasFactory;
    use HasAdvancedFilter;
    use SoftDeletes;

    public const ATTRIBUTES = [
        'id',
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
        'user_id',
        'title',
        'comment',
        'initial',
        'start',
        'end',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(
            related: User::class,
            foreignKey: 'user_id',
        );
    }

    public function sales(): HasMany
    {
        return $this->hasMany(Sale::class, 'cash_id', 'id');
    }

    public function sale_payments(): HasMany
    {
        return $this->hasMany(SalePayment::class, 'cash_id', 'id');
    }

    public function expenses(): HasMany
    {
        return $this->hasMany(Expense::class, 'cash_id', 'id');
    }

    public function getTotalExpensesAttribute()
    {
        return $this->expenses->where('is_expense', true)->sum(function($children) {
          return $children->amount;
        });
    }

    public function getTotalIncomesAttribute()
    {
        return $this->expenses->where('is_expense', false)->sum(function($children) {
          return $children->amount;
        });
    }

    public function getTotalCashAttribute()
    {
        return $this->sale_payments->where('payment_method', 'Cash')->sum(function($children) {
          return $children->amount;
        });
    }

    public function getTotalOtherAttribute()
    {
        return $this->sale_payments->where('payment_method', '<>', 'Cash')->sum(function($children) {
          return $children->amount;
        });
    }

    public function getInitialAttribute()
    {
        return (integer) $this->attributes['initial'];
    }

}
