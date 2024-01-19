<?php

declare(strict_types=1);

namespace App\Models;

use App\Support\HasAdvancedFilter;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Scope\DateScope;

class Expense extends Model
{
    use HasAdvancedFilter;
    use HasFactory;
    use SoftDeletes;
    use DateScope;

    public const ATTRIBUTES = [
        'id',
        'date',
        'reference',
        'amount',
        'category_id',
        'details',
    ];

    public $orderable = self::ATTRIBUTES;
    public $filterable = self::ATTRIBUTES;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'category_id',
        'user_id',
        'warehouse_id',
        'date',
        'reference',
        'details',
        'amount',
    ];

    public function __construct(array $attributes = [])
    {
        $this->setRawAttributes([
            'reference' => 'EXP-'.Carbon::now()->format('d/m/Y'),
            'date'      => Carbon::now()->format('d/m/Y'),
        ], true);
        parent::__construct($attributes);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(ExpenseCategory::class, 'category_id')->withTrashed();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id')->withTrashed();
    }

    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class, 'warehouse_id')->withTrashed();
    }

    /**
     * @param mixed $query
     *
     * @return mixed
     */
    public function scopeIncomes($query)
    {
        return $query->whereIsExpense(false);
    }

    /**
     * @param mixed $query
     *
     * @return mixed
     */
    public function scopeExpenses($query)
    {
        return $query->whereIsExpense(true);
    }
}
