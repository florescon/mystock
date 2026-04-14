<?php

declare(strict_types=1);

namespace App\Scopes;

trait CashOutScope
{
    public function scopeWithoutCash($query)
    {
        return $query->where('cash_id', NULL);
    }

    public function scopeOnlyPaymentCash($query)
    {
        return $query->where('payment_method', 'Cash');
    }

    public function scopeOtherPaymentMethod($query)
    {
        return $query->where('payment_method', '<>', 'Cash');
    }

}
