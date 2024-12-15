<?php

namespace Tests\Unit;

use App\Support\ProductDataHelper;
use PHPUnit\Framework\TestCase;

class ProductDataHelperTest extends TestCase
{
    /**
     * A basic unit test example.
     */
    public function test_string_format(): void
    {
        $this->assertEquals('tested', ProductDataHelper::formatString('  tested  '));
        $this->assertEquals('', ProductDataHelper::formatString(''));
        $this->assertEquals('', ProductDataHelper::formatString(null));
    }

    public function test_integer_format(): void
    {
        $this->assertEquals(123, ProductDataHelper::formatInteger('123'));
        $this->assertEquals(123, ProductDataHelper::formatInteger(' a123abc '));
        $this->assertEquals(null, ProductDataHelper::formatInteger('abc'));
        $this->assertEquals(null, ProductDataHelper::formatInteger(''));
        $this->assertEquals(null, ProductDataHelper::formatInteger(null));
    }

    public function test_format_float()
    {
        $this->assertEquals(123.45, ProductDataHelper::formatFloat('123.45'));
        $this->assertEquals(123.45, ProductDataHelper::formatFloat(' aa123.45abc '));
        $this->assertEquals(123.0, ProductDataHelper::formatFloat('123'));
        $this->assertEquals(null, ProductDataHelper::formatFloat('abc'));
        $this->assertEquals(null, ProductDataHelper::formatFloat(''));
        $this->assertEquals(null, ProductDataHelper::formatFloat(null));
    }

    public function test_format_boolean()
    {
        $this->assertTrue(ProductDataHelper::formatBoolean('true'));
        $this->assertTrue(ProductDataHelper::formatBoolean('yes'));
        $this->assertTrue(ProductDataHelper::formatBoolean('1'));
        $this->assertTrue(ProductDataHelper::formatBoolean(1));
        $this->assertFalse(ProductDataHelper::formatBoolean('false'));
        $this->assertFalse(ProductDataHelper::formatBoolean('blahblah'));
        $this->assertFalse(ProductDataHelper::formatBoolean('no'));
        $this->assertFalse(ProductDataHelper::formatBoolean('0'));
        $this->assertFalse(ProductDataHelper::formatBoolean(0));
        $this->assertFalse(ProductDataHelper::formatBoolean(''));
        $this->assertFalse(ProductDataHelper::formatBoolean(null));
    }

    public function test_general_format()
    {
        $mockData = [
            'Product Code' => ' P001 ',
            'Product Name' => '  Product  ',
            'Product Description' => ' Some description ',
            'Stock' => ' 12 ',
            'Cost in GBP' => ' £45.67 ',
            'Discontinued' => 'yes',
        ];

        $expectedFormattedData = [
            'Product Code' => 'P001',
            'Product Name' => 'Product',
            'Product Description' => 'Some description',
            'Stock' => 12,
            'Cost in GBP' => 45.67,
            'Discontinued' => true,
        ];

        $this->assertEquals($expectedFormattedData, ProductDataHelper::format($mockData));
    }
}
