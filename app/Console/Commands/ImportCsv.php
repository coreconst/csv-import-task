<?php

namespace App\Console\Commands;

use App\Action\ConvertCsvToArray;
use App\Data\Product;
use App\Enums\ProductDataColumn;
use App\Models\ProductData;
use App\Support\ProductDataHelper;
use Carbon\Carbon;
use Illuminate\Console\Command;

class ImportCsv extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:csv {file : The path to the CSV file} {--test : Allows to run the command in a "test mode", where the data is processed, validated, and formatted without being imported into the database}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import product data from a CSV file into the database';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $filePath = $this->argument('file');
        $isTestMode = $this->option('test');

        $this->info('Loading CSV file...');
        $entries = ConvertCsvToArray::run($filePath);

        if(empty($entries)){
            $this->error("Failed to load CSV file: $filePath");
            return 1;
        }

        $this->info('Processing products data...');
        $importedProductsCount = 0;
        $skippedProducts = [];
        foreach ($entries as $entry){
            $formattedData = ProductDataHelper::format($entry);

            $product = new Product(
                code: $formattedData[ProductDataColumn::Code->label()],
                name: $formattedData[ProductDataColumn::Name->label()],
                description: $formattedData[ProductDataColumn::Description->label()],
                stock: $formattedData[ProductDataColumn::Stock->label()],
                cost: $formattedData[ProductDataColumn::Cost->label()],
                discontinued: $formattedData[ProductDataColumn::Discontinued->label()]
            );

            if($product->isValidToImport()){
                if(!$isTestMode){
                    $this->addProductToDatabase($product->validatedData());
                }

                $importedProductsCount++;
            } else {
                $skippedProducts[] = $product->code;
            }
        }

        $this->info("Import completed. ".count($entries)." items were processed. Successfully imported: $importedProductsCount products.");

        if(!empty($skippedProducts)){
            $this->info('Products were skipped:');
            foreach ($skippedProducts as $product){
                $this->line($product);
            }
        }

        return 0;
    }

    protected function addProductToDatabase(array $product): void
    {
        if(empty($product)) return;

        ProductData::firstOrCreate(
            [
                'strProductCode' => $product['code']
            ],
            [
                'strProductName' => $product['name'],
                'strProductDesc' => $product['description'],
                'intStock' => $product['stock'],
                'decPrice' => $product['cost'],
                'dtmAdded' => Carbon::now(),
                'dtmDiscontinued' => $product['discontinued'] ? Carbon::now() : null,
            ]
        );
    }

}
