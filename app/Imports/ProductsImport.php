<?php

namespace App\Imports;

use App\Product;
use App\Category;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsErrors;

class ProductsImport implements
    ToModel,
    WithHeadingRow,
    WithValidation,
    SkipsOnFailure,
    SkipsOnError
{
    use Importable, SkipsFailures, SkipsErrors;

    public $count = 0;

    public function model(array $row)
    {
        $category = Category::where('title', $row['kategoriya_tovara'])->first();
        if(!$category) {
            $category = Category::create([
                'title' => $row['kategoriya_tovara'],
                'rubric' => $row['rubrika'],
                'brand' => $row['proizvoditel'],
            ]);
        }

        $product = new Product([
            'name'          => $row['naimenovanie_tovara'],
            'code'          => $row['kod_modeli_artikul_proizvoditelya'],
            'description'   => $row['opisanie_tovara'],
            'price'         => $row['tsena_rozn_grn'],
            'guarantee'     => $row['garantiya'],
            'available'     => $row['nalichie'] == 'нет в наличие' ? 0 : 1,
        ]);

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
            '*.kod_modeli_artikul_proizvoditelya' => 'bail|required|alpha_num|between:5,20|unique:products,code',
            '*.opisanie_tovara' => 'required',
            '*.tsena_rozn_grn' => 'bail|required|integer',
            '*.garantiya' => 'bail|required|max:3',
            '*.nalichie' => 'required',
        ];
    }

}
