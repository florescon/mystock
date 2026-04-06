<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'customer_id',
        'sale_details_service_id',
        'time_day',
    ];

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

    public function sale_details_service(): BelongsTo
    {
        return $this->belongsTo(SaleDetailsService::class, 'sale_details_service_id', 'id');
    }
}
