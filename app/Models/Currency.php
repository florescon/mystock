<?php

declare(strict_types=1);

namespace App\Models;

use App\Support\HasAdvancedFilter;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Currency extends Model
{
    use HasAdvancedFilter;
    use HasFactory;
    use SoftDeletes;

    public const ATTRIBUTES = [
        'id',
        'name',
        'code',
        'symbol',
        'exchange_rate',
        'updated_at',
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
        'name',
        'code',
        'symbol',
        'thousand_separator',
        'decimal_separator',
        'exchange_rate',
    ];
}
