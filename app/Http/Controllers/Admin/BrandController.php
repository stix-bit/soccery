<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\BrandDataTable;
use App\Http\Controllers\Controller;
use App\Imports\BrandsImport;
use App\Models\Brand;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;

class BrandController extends Controller
{
    public function index(BrandDataTable $dataTable)
    {
        return $dataTable->render('admin.brands.index');
    }

    public function create(): View
    {

        $brands = Brand::orderBy('id')->pluck('name', 'id');

        return view('admin.brands.create', compact('brands'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
        ]);

        $brand = Brand::create($validated);
       
        return redirect()->route('admin.brands.index')
            ->with('status', 'Brand created successfully.');
    }

    public function edit(Brand $brand): View
    {
        $allBrands = Brand::orderBy('id')->pluck('name', 'id'); // optional, for dropdowns etc.

            return view('admin.brands.edit', [
                'brand' => $brand,     // single brand for editing
                'brands' => $allBrands // collection for dropdowns if needed
            ]);
    }

    public function update(Request $request, Brand $brand): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
        ]);

        $brand->update($validated);

        return redirect()->route('admin.brands.index')
            ->with('status', 'Brand updated successfully.');
    }

    public function destroy(Brand $brand): RedirectResponse
    {
        $brand->delete();

        return redirect()->route('admin.brands.index')
            ->with('status', 'Brand archived successfully.');
    }

    public function restore(int $brand): RedirectResponse
    {
        $model = Brand::withTrashed()->findOrFail($brand);
        $model->restore();

        return redirect()->route('admin.brands.index')
            ->with('status', 'Brand restored successfully.');
    }

    public function import(Request $request): RedirectResponse
    {
        $request->validate([
            'import_file' => ['required', 'file', 'mimes:xlsx,xls,csv', 'max:5120'],
        ]);

        $import = new BrandsImport();

        try {
            Excel::import($import, $request->file('import_file'));
        } catch (\Throwable $exception) {
            return redirect()->route('admin.brands.index')
                ->with('status', 'Brand import failed. Please check your file format and headings.');
        }

        $failedRows = count($import->failures()) + count($import->errors());
        $message = 'Brands imported successfully.';

        if ($failedRows > 0) {
            $message .= " {$failedRows} row(s) were skipped due to validation or processing errors.";
        }

        return redirect()->route('admin.brands.index')->with('status', $message);
    }
}
