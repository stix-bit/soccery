<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\CategoryDataTable;
use App\Http\Controllers\Controller;
use App\Imports\CategoriesImport;
use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;

class CategoryController extends Controller
{
    public function index(CategoryDataTable $dataTable)
    {
        return $dataTable->render('admin.categories.index');
    }

    public function create(): View
    {

        $categories = Category::orderBy('id')->pluck('name', 'id');

        return view('admin.categories.create', compact('categories'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
        ]);

        $category = Category::create($validated);
       
        return redirect()->route('admin.categories.index')
            ->with('status', 'Category created successfully.');
    }

    public function edit(Category $category): View
    {
        $allCategories = Category::orderBy('id')->pluck('name', 'id'); // optional, for dropdowns etc.

            return view('admin.categories.edit', [
                'category' => $category,     // single category for editing
                'categories' => $allCategories // collection for dropdowns if needed
            ]);
    }

    public function update(Request $request, Category $category): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
        ]);

        $category->update($validated);

        return redirect()->route('admin.categories.index')
            ->with('status', 'Category updated successfully.');
    }

    public function destroy(Category $category): RedirectResponse
    {
        $category->delete();

        return redirect()->route('admin.categories.index')
            ->with('status', 'Category archived successfully.');
    }

    public function restore(int $category): RedirectResponse
    {
        $model = Category::withTrashed()->findOrFail($category);
        $model->restore();

        return redirect()->route('admin.categories.index')
            ->with('status', 'Category restored successfully.');
    }

    public function import(Request $request): RedirectResponse
    {
        $request->validate([
            'import_file' => ['required', 'file', 'mimes:xlsx,xls,csv', 'max:5120'],
        ]);

        $import = new CategoriesImport();

        try {
            Excel::import($import, $request->file('import_file'));
        } catch (\Throwable $exception) {
            return redirect()->route('admin.categories.index')
                ->with('status', 'Category import failed. Please check your file format and headings.');
        }

        $failedRows = count($import->failures()) + count($import->errors());
        $message = 'Categories imported successfully.';

        if ($failedRows > 0) {
            $message .= " {$failedRows} row(s) were skipped due to validation or processing errors.";
        }

        return redirect()->route('admin.categories.index')->with('status', $message);
    }
}
