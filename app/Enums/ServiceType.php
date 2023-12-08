<?php

declare(strict_types=1);

namespace App\Enums;

use Illuminate\Support\Str;

enum ServiceType: int
{
    case INSCRIPTION = 0;

    case MONTHLYPAYMENT = 1;

    case FREEPASS = 2;

    case OTHER = 3;

    public function getName(): string
    {
        return __(Str::studly($this->name));
    }

    public function getValue()
    {
        return $this->value;
    }

    public static function getLabel($value)
    {
        foreach (self::cases() as $case) {
            if ($case->getValue() === $value) {
                return $case->getName();
            }
        }

        return null;
    }

    public function getBadgeType(): string
    {
        switch ($this) {
            case self::INSCRIPTION:
                return 'warning';
            case self::MONTHLYPAYMENT:
                return 'info';
            case self::FREEPASS:
                return 'success';
            default:
                return 'secondary';
        }
    }

    public function getBannerType(): string
    {
        switch ($this) {
            case self::INSCRIPTION:
                return 'inscription.jpg';
            case self::MONTHLYPAYMENT:
                return 'monthlypayment.jpg';
            case self::FREEPASS:
                return 'freepass.jpg';
            default:
                return 'other.jpg';
        }
    }
}
