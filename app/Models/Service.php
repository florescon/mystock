<?php

namespace App\Models;

use App\Enums\ServiceType;
use App\Support\HasAdvancedFilter;
use App\Traits\GetModelByUuid;
use App\Traits\UuidGenerator;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Service extends Model
{
    use HasFactory;
    use HasAdvancedFilter;
    use GetModelByUuid;
    use UuidGenerator;
    use SoftDeletes;

    public const ATTRIBUTES = [
        'id',
        'name',
        'price',
        'code',
        'service_type',
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
        'uuid',
        'name',
        'code',
        'price',
        'status',
        'tax_type',
        'note',
        'image',
        'featured',
        'service_type',
    ];

    protected $casts = [
        'service_type'         => ServiceType::class,
    ];

    public function __construct(array $attributes = [])
    {
        $this->setRawAttributes([

            'code' => Carbon::now()->format('Y-').mt_rand(10000000, 99999999),

        ], true);
        parent::__construct($attributes);
    }

}
