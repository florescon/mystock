<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Casts\Attribute;

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

    protected function userInitials(): Attribute
    {
        return Attribute::make(
            get: function () {
                if (!$this->user?->name) {
                    return null;
                }

                return collect(explode(' ', $this->user->name))
                    ->map(function ($word) {
                        return ucfirst(strtolower(substr($word, 0, 3)));
                    })
                    ->implode('. ') . '.';
            }

        );
    }

    protected function conceptInitials(): Attribute
    {
        return Attribute::make(
            get: function () {
                if (!$this->sale_details_service?->name) {
                    return null;
                }

                return collect(explode(' ', $this->sale_details_service->name))
                    ->map(function ($word) {
                        return ucfirst(strtolower(substr($word, 0, 3)));
                    })
                    ->implode('. &nbsp;') . '.';
            }

        );
    }

    public function getDateDiffForHumansCreatedAttribute()
    {
        return $this->updated_at->isoFormat('D, MMM, YY h:mm a');
    }

    public function getDateDiffForHumansCreatedShortAttribute()
    {
        return $this->updated_at->isoFormat('D, MMM, YY');
    }

}
