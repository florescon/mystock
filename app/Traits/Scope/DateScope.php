<?php

declare(strict_types=1);

namespace App\Traits\Scope;

use Exception;
use Illuminate\Support\Carbon;

trait DateScope
{
    public static function scopeDatesForPeriod($query, $period)
    {
        switch ($period) {
            case 'today':
                $start = now()->startOfDay();
                $end = now()->endOfDay();
                break;
            case 'yesterday':
                $start = now()->subDay()->startOfDay();
                $end = now()->subDay()->endOfDay();
                break;
            case 'last 24 hours':
                $start = now()->subHours(24);
                $end = now()->endOfDay();
                break;
            case 'last 7 days':
                $start = now()->subDays(6)->startOfDay();
                $end = now()->endOfDay();
                break;
            case 'this week':
                $start = now()->startOfWeek();
                $end = now()->endOfDay();
                break;
            case 'last 30 days':
                $start = now()->subDays(29)->startOfDay();
                $end = now()->endOfDay();
                break;
            case 'this month':
                $start = now()->startOfMonth();
                $end = now()->endOfDay();
                break;
            case 'last 90 days':
                $start = now()->subDays(89)->startOfDay();
                $end = now()->endOfDay();
                break;
            case 'last month':
                $start = now()->subMonth()->startOfMonth();
                $end = now()->subMonth()->endOfMonth();
                break;
            case 'quarter to date':
                $start = now()->startOfQuarter();
                $end = now()->endOfDay();
                break;
            case 'this year':
                $start = now()->startOfYear();
                $end = now()->endOfDay();
                break;
            case 'last year':
                $start = now()->subYear()->startOfYear();
                $end = now()->subYear()->endOfYear();
                break;
            case 'all time':
                $start = Carbon::createFromDate(1900, 1, 1)->startOfDay();
                $end = Carbon::today()->endOfDay();
                break;
            case 'custom':
                if ($customStart && $customEnd) {
                    $start = Carbon::parse($customStart)->startOfDay();
                    $end = Carbon::parse($customEnd)->endOfDay();
                } else {
                    throw new Exception('Custom range requires start and end dates.');
                }
                break;
            default:
                throw new Exception('Invalid period specified.');
        }

        return $query->whereBetween('created_at', [$start, $end]);
    }
}