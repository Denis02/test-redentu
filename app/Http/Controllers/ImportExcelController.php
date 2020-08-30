<?php

namespace App\Http\Controllers;

use App\Rules\UploadMaxFilesize;
use Illuminate\Http\Request;

use App\Imports\ProductsImport;

class ImportExcelController extends Controller
{
    public function index()
    {
        return view('import');
    }

    public function store(Request $request)
    {
        $validator = \Validator::make($request->file(), [
            'file' => ['required', 'mimes:xlsx,xls,csv', new UploadMaxFilesize]
        ]);
        if ($validator->fails()) {
            return back()
                ->withErrors($validator);
        }

        $file = request()->file('file');
        ini_set('max_execution_time', 600);

        $import = new ProductsImport();
        $import->import($file);

        if($import->errors()->count()){
            return back()->withErrors($import->errors());
        }

        return back()->with([
            'status' => "{$import->count} products imported!",
            'failures' => $import->failures()
        ]);
    }
}
