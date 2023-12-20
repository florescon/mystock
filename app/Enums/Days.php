<?php

declare(strict_types=1);

namespace App\Enums;

use Illuminate\Support\Str;

enum Days: int
{
    case LUNES = 0;

    case MARTES = 1;

    case MIERCOLES = 2;

    case JUEVES = 3;

    case VIERNES = 4;

    case SABADO = 5;

    public function getName(): string
    {
        return __(Str::studly($this->name));
    }

    public function getBadgeType(): string
    {
        switch ($this) {
            case self::LUNES:
                return 'warning';
            case self::MARTES:
                return 'primary';
            case self::MIERCOLES:
                return 'success';
            case self::JUEVES:
                return 'info';
            case self::VIERNES:
                return 'danger';
            case self::SABADO:
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
