<?php

namespace App\Action;

use Exception;
use PhpOffice\PhpSpreadsheet\IOFactory;

class ConvertCsvToArray
{
    /**
     * @param string $filePath
     * @return array|null
     */
    public static function run(string $filePath): ?array
    {
        try {
            if(!file_exists($filePath) || (IOFactory::identify($filePath) !== 'Csv')){
                throw new Exception('File not exists');
            }
            $spreadsheet = IOFactory::load($filePath);
            $worksheet = $spreadsheet->getActiveSheet();
            $rows = $worksheet->toArray();
            $columns = $rows[0];

            $data = [];
            foreach (array_slice($rows, 1) as $row) {
                $data[] = array_combine($columns, $row);
            }

            return $data;
        } catch (Exception $e) {
            echo $e->getMessage(), "\n";
            return null;
        }
    }
}
