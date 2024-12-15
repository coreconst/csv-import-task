<?php

namespace App\Enums;

enum ProductDataColumn: string
{
    case Code = 'code';
    case Name = 'name';
    case Description = 'description';
    case Stock = 'stock';
    case Cost = 'cost';
    case Discontinued = 'discontinued';

    public function label(): string
    {
        return match ($this) {
            self::Code => 'Product Code',
            self::Name => 'Product Name',
            self::Description => 'Product Description',
            self::Stock => 'Stock',
            self::Cost => 'Cost in GBP',
            self::Discontinued => 'Discontinued',
        };
    }

    public function type(): string
    {
        return match ($this) {
            self::Stock => 'integer',
            self::Cost => 'float',
            self::Discontinued => 'boolean',
            default => 'string'
        };
    }
}
