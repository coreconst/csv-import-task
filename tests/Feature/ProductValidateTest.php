<?php

namespace Tests\Feature;

use App\Data\Product;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

class ProductValidateTest extends TestCase
{
    public function test_product_is_valid_to_import()
    {
        $product = new Product(
            code: 'P001',
            name: 'Test Product',
            description: 'Description here',
            stock: 20,
            cost: 100.50,
            discontinued: false
        );

        $this->assertTrue($product->isValidToImport());
        $this->assertEmpty($product->getValidationFailedMessages());
    }

    public function test_product_validation_fails_with_invalid_data()
    {
        $product = new Product(
            code: '',
            name: '',
            description: '',
            stock: null,
            cost: null,
            discontinued: false
        );

        $this->assertFalse($product->isValidToImport());
        $this->assertArrayHasKey('code', $product->getValidationFailedMessages());
        $this->assertArrayHasKey('name', $product->getValidationFailedMessages());
        $this->assertArrayHasKey('stock', $product->getValidationFailedMessages());
        $this->assertArrayHasKey('cost', $product->getValidationFailedMessages());
    }

    public function test_validated_data_returns_clean_data()
    {
        $product = new Product(
            code: 'P001',
            name: 'Test Product',
            description: 'Description here',
            stock: 20,
            cost: 100.50,
            discontinued: true
        );

        $validatedData = $product->validatedData();
        $this->assertNotEmpty($validatedData);

        $this->assertEquals('P001', $validatedData['code']);
        $this->assertEquals('Test Product', $validatedData['name']);
        $this->assertEquals(20, $validatedData['stock']);
        $this->assertEquals(100.50, $validatedData['cost']);
        $this->assertTrue($validatedData['discontinued']);
    }
}
