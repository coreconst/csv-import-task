<?php

namespace App\Data;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class Product
{
    protected array $validateRules = [
        'code' => 'required|string',
        'name' => 'required|string',
        'description' => 'required|string',
        'stock' => 'integer|min:10',
        'cost' => 'numeric|min:5|max:1000',
        'discontinued' => 'boolean',
    ];

    protected \Illuminate\Validation\Validator $validator;

    public function __construct(
        public string $code,
        public string $name,
        public string $description,
        public ?int $stock,
        public ?float $cost,
        public bool $discontinued,
    ) {
        $this->validator = Validator::make($this->toArray(), $this->validateRules);
    }

    public function isValidToImport(): bool
    {
        return !$this->validator->fails();
    }

    public function getValidationFailedMessages(): array
    {
        return $this->isValidToImport() ? [] : $this->validator->errors()->toArray();
    }

    public function validatedData(): array
    {
        try {
            return $this->validator->validated();
        } catch (ValidationException $e){
            Log::error($e->getMessage());
            return [];
        }

    }

    public function toArray(): array
    {
        return [
            'code' => $this->code,
            'name' => $this->name,
            'description' => $this->description,
            'stock' => $this->stock,
            'cost' => $this->cost,
            'discontinued' => $this->discontinued,
        ];
    }
}
