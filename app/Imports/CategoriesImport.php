<?php

namespace App\Imports;

use App\Models\Category;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class CategoriesImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnFailure, SkipsOnError
{
    use Importable;
    use \Maatwebsite\Excel\Concerns\SkipsErrors;
    use \Maatwebsite\Excel\Concerns\SkipsFailures;

    public function model(array $row): ?Category
    {
        $name = trim((string) ($row['name'] ?? ''));

        if ($name === '') {
            return null;
        }

        $category = Category::withTrashed()->firstOrNew(['name' => $name]);

        if ($category->exists && $category->trashed()) {
            $category->restore();
        }

        $category->description = $this->nullableString($row['description'] ?? null);
        $category->save();

        return $category;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
        ];
    }

    public function customValidationMessages(): array
    {
        return [
            'name.required' => 'The category name column is required.',
        ];
    }

    private function nullableString(mixed $value): ?string
    {
        $string = trim((string) $value);

        return $string === '' ? null : $string;
    }
}
