<?php

namespace Tests\Unit;

use App\Action\ConvertCsvToArray;
use PHPUnit\Framework\TestCase;

class ConvertCsvTest extends TestCase
{
    protected string $path;

    protected array $expectedFirstRow;

    protected int $amountOfEntries;

    protected function setUp(): void
    {
        parent::setUp(); // TODO: Change the autogenerated stub

        $this->path = __DIR__ . '/../../data/stock.csv';
        $this->expectedFirstRow = [
            'Product Code' => 'P0001',
            'Product Name' => 'TV',
            'Product Description' => '32” Tv',
            'Stock' => 10,
            'Cost in GBP' => 399.99,
            'Discontinued' => null,
        ];
        $this->amountOfEntries = 29;
    }

    public function test_convert_csv_to_array_handle_invalid_path(): void
    {
        $path = 'invalid.csv';

        $result = ConvertCsvToArray::run($path);
        $this->assertNull($result);
    }

    public function test_convert_csv_to_array_return_correct_values(): void
    {
        $result = ConvertCsvToArray::run($this->path);

        $this->assertIsArray($result);
        $this->assertCount($this->amountOfEntries, $result);
        $this->assertEquals($this->expectedFirstRow, $result[0]);
    }
}