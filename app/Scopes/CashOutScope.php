<?php

declare(strict_types=1);

namespace App\Scopes;

trait CashOutScope
{
    public function scopeWithoutCash($query)
    {
        return $query->where('cash_id', NULL);
    }
}
