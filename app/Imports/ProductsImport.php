<?php

namespace App\Imports;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class ProductsImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnFailure, SkipsOnError
{
    use Importable;
    use \Maatwebsite\Excel\Concerns\SkipsErrors;
    use \Maatwebsite\Excel\Concerns\SkipsFailures;

    public function model(array $row): ?Product
    {
        $name = trim((string) ($row['name'] ?? ''));

        if ($name === '') {
            return null;
        }

        $category = $this->resolveCategory($row);
        $brand = $this->resolveBrand($row);

        if (! $category || ! $brand) {
            return null;
        }

        $product = Product::withTrashed()->firstOrNew(['name' => $name]);

        if ($product->exists && $product->trashed()) {
            $product->restore();
        }

        $product->fill([
            'category_id' => $category->id,
            'brand_id' => $brand->id,
            'name' => $name,
            'description' => trim((string) $row['description']),
            'price' => (float) $row['price'],
            'stock' => (int) $row['stock'],
        ]);

        $product->save();

        return $product;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'stock' => ['required', 'integer', 'min:0'],
            'category_id' => [
                'nullable',
                'integer',
                'required_without:category_name',
                Rule::exists('categories', 'id')->where(static fn ($query) => $query->whereNull('deleted_at')),
            ],
            'category_name' => [
                'nullable',
                'string',
                'required_without:category_id',
                function (string $attribute, mixed $value, \Closure $fail): void {
                    if (blank($value)) {
                        return;
                    }

                    $exists = Category::query()
                        ->whereRaw('LOWER(name) = ?', [mb_strtolower(trim((string) $value))])
                        ->whereNull('deleted_at')
                        ->exists();

                    if (! $exists) {
                        $fail('The selected category_name is invalid.');
                    }
                },
            ],
            'brand_id' => [
                'nullable',
                'integer',
                'required_without:brand_name',
                Rule::exists('brands', 'id')->where(static fn ($query) => $query->whereNull('deleted_at')),
            ],
            'brand_name' => [
                'nullable',
                'string',
                'required_without:brand_id',
                function (string $attribute, mixed $value, \Closure $fail): void {
                    if (blank($value)) {
                        return;
                    }

                    $exists = Brand::query()
                        ->whereRaw('LOWER(name) = ?', [mb_strtolower(trim((string) $value))])
                        ->whereNull('deleted_at')
                        ->exists();

                    if (! $exists) {
                        $fail('The selected brand_name is invalid.');
                    }
                },
            ],
        ];
    }

    private function resolveCategory(array $row): ?Category
    {
        if (! empty($row['category_id'])) {
            return Category::query()
                ->whereNull('deleted_at')
                ->find((int) $row['category_id']);
        }

        if (empty($row['category_name'])) {
            return null;
        }

        return Category::query()
            ->whereRaw('LOWER(name) = ?', [mb_strtolower(trim((string) $row['category_name']))])
            ->whereNull('deleted_at')
            ->first();
    }

    private function resolveBrand(array $row): ?Brand
    {
        if (! empty($row['brand_id'])) {
            return Brand::query()
                ->whereNull('deleted_at')
                ->find((int) $row['brand_id']);
        }

        if (empty($row['brand_name'])) {
            return null;
        }

        return Brand::query()
            ->whereRaw('LOWER(name) = ?', [mb_strtolower(trim((string) $row['brand_name']))])
            ->whereNull('deleted_at')
            ->first();
    }
}
