<?php

namespace App\Imports;

use App\Product;
use App\Category;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsErrors;

class ProductsImport implements
    ToModel,
    WithHeadingRow,
    WithValidation,
    WithBatchInserts,
    WithChunkReading,
    SkipsOnFailure,
    SkipsOnError
{
    use Importable, SkipsFailures, SkipsErrors;

    public $count = 0;

    public function model(array $row)
    {
        $category = Category::where('title', $row['kategoriya_tovara'])->first();
        if(!$category) {
            $category = new Category([
                'title' => $row['kategoriya_tovara'],
                'rubric' => $row['rubrika'] ?? $row['kategoriya_tovara'],
                'brand' => $row['proizvoditel'],
            ]);
            $category->save();
        }

        $product = new Product([
            'name'          => $row['naimenovanie_tovara'],
            'code'          => $row['kod_modeli_artikul_proizvoditelya'],
            'description'   => $row['opisanie_tovara'],
            'price'         => $row['tsena_rozn_grn'],
            'guarantee'     => $row['garantiya'],
            'available'     => $row['nalichie'] == 'нет в наличие' ? 0 : 1,
        ]);

        //$category->products()->save($product);
        $product->category_id = $category->id;
        ++$this->count;
        return $product;
    }

    public function rules(): array
    {
        return [
            '*.kategoriya_tovara' => 'required',
            '*.rubrika' => 'required',
            '*.proizvoditel' => 'required',
            '*.naimenovanie_tovara' => 'required',
            '*.kod_modeli_artikul_proizvoditelya' => 'required|alpha_num|max:20|min:5|unique:products,code',
            '*.opisanie_tovara' => 'required',
            '*.tsena_rozn_grn' => 'required|integer',
            '*.garantiya' => 'required|max:3',
            '*.nalichie' => 'required',
        ];
    }
//    public function customValidationMessages()
//    {
//        return [
//        ];
//    }

    public function batchSize(): int
    {
        return 100;
    }

    public function chunkSize(): int
    {
        return 100;
    }

}
