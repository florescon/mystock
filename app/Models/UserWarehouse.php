<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserWarehouse extends Model
{
    use SoftDeletes;

    protected $table = 'user_warehouse';

    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id', 'warehouse_id',
    ];

    protected $casts = [
        'user_id'      => 'integer',
        'warehouse_id' => 'integer',
    ];

    /** @return HasMany<Warehouse> */
    public function assignedWarehouses(): HasMany
    {
        return $this->hasMany(Warehouse::class, 'id', 'warehouse_id');
    }
}
