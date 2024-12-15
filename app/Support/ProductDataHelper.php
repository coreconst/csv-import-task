<?php

namespace App\Support;

use App\Enums\ProductDataColumn;

class ProductDataHelper
{
    private static array $booleanAcceptableValues = ['true', 'yes', '1', 1];

    public static function format(array $data): array
    {
        foreach (ProductDataColumn::cases() as $case){
            $value = $data[$case->label()];
            $data[$case->label()] = match ($case->type()){
                'string' => self::formatString($value),
                'integer' => self::formatInteger($value),
                'float' => self::formatFloat($value),
                'boolean' => self::formatBoolean($value)
            };
        }

        return $data;
    }


    public static function formatString(?string $value): string
    {
        return !empty($value) ? trim($value) : '';
    }

    public static function formatInteger(?string $value): ?int
    {
        return !empty($value) && preg_match('/\d+/', $value, $matches)
            ? (int)$matches[0]
            : null;
    }

    public static function formatFloat(?string $value): ?float
    {
        return !empty($value) && preg_match('/\d+(\.\d+)?/', $value, $matches)
            ? (float)$matches[0]
            : null;
    }

    public static function formatBoolean(?string $value): bool
    {
        $value = strtolower(self::formatString($value));

        return in_array($value, self::$booleanAcceptableValues);
    }
}
