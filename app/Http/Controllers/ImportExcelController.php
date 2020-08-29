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
        $import = new ProductsImport();
        $import->import(request()->file('file'));

        dd([$import->failures(),$import->errors()]);

        return back();
    }
}
