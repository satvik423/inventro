<?php

namespace App\Imports;

use App\Models\Product;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ProductsImport implements ToModel, WithHeadingRow
{

    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        // dd($row);
        logger($row);
        return new Product([
            'name'        => $row['name'],
            'description' => $row['description'],
            'price'       => (int) $row['price'],
            'stock'       => (int) $row['stock'],
            'category_id' => (int) $row['category_id'],
        ]);
    }
}
