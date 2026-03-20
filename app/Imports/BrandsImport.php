<?php

namespace App\Imports;

use App\Models\Brand;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class BrandsImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnFailure, SkipsOnError
{
    use Importable;
    use \Maatwebsite\Excel\Concerns\SkipsErrors;
    use \Maatwebsite\Excel\Concerns\SkipsFailures;

    public function model(array $row): ?Brand
    {
        $name = trim((string) ($row['name'] ?? ''));

        if ($name === '') {
            return null;
        }

        $brand = Brand::withTrashed()->firstOrNew(['name' => $name]);

        if ($brand->exists && $brand->trashed()) {
            $brand->restore();
        }

        $brand->description = $this->nullableString($row['description'] ?? null);
        $brand->save();

        return $brand;
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
            'name.required' => 'The brand name column is required.',
        ];
    }

    private function nullableString(mixed $value): ?string
    {
        $string = trim((string) $value);

        return $string === '' ? null : $string;
    }
}
