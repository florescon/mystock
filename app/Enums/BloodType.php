<?php

declare(strict_types=1);

namespace App\Enums;

use Illuminate\Support\Str;

enum BloodType: int
{
    case APOSITIVE = 0;

    case ANEGATIVE = 1;

    case BPOSITIVE = 2;

    case BNEGATIVE = 3;

    case ABPOSITIVE = 4;

    case ABNEGATIVE = 5;

    case OPOSITIVE = 6;

    case ONEGATIVE = 7;

    public function getName(): string
    {
        return __(Str::studly($this->name));
    }

    public function getBadgeType(): string
    {
        switch ($this) {
            case self::APOSITIVE:
                return 'warning';
            case self::ANEGATIVE:
                return 'primary';
            case self::BPOSITIVE:
                return 'success';
            case self::BNEGATIVE:
                return 'info';
            case self::ABPOSITIVE:
                return 'danger';
            case self::ABNEGATIVE:
                return 'secondary';
            case self::OPOSITIVE:
                return 'danger';
            case self::ONEGATIVE:
                return 'secondary';
            default:
                return 'secondary';
        }
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
}
