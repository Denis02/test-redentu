<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

use App\Imports\ProductsImport;

class ImportExcelController extends Controller
{
    public function index()
    {
        return view('import');
    }

    public function store(Request $request)
    {
        $max_filesize = (int)ini_get('upload_max_filesize')*1024;
        $validator = \Validator::make($request->file(), [
            'file' => "required|max:$max_filesize|mimes:xlsx"
        ]);
        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }

        $file = request()->file('file')->store('import');

        $import = new ProductsImport();
        $import->import($file);

        return back()->with([
            'status' => "{$import->count} products imported",
            'errors' => $import->errors()->toArray(),
            'failures' => $import->failures()
        ]);
    }
}
