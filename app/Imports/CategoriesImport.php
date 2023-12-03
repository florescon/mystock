<?php

declare(strict_types=1);

namespace App\Imports;

use App\Models\Category;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Validators\Failure;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\WithValidation;

class CategoriesImport implements ToModel, WithHeadingRow, SkipsEmptyRows, SkipsOnFailure, WithValidation
{
    /**  */
    public function __construct()
    {
    }

    /**
     * @param array $row
     *
     * @return \App\Models\Category
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        $cat = new Category();

        if(isset($row['name']) && isset($row['code'])){
            return $cat->firstOrCreate([
                'code' => $row['code'] ?? Str::random(5)],
                ['name' => $row['name'],
            ]);
        }

    }

    public function rules(): array
    {
        return [
            'code' => [
                'unique:categories',
                'max:100',
            ],
            'name' => [
                'max:100',
            ],
        ];
    }

    /**
     * @param Failure[] $failures
     */
    public function onFailure(Failure ...$failures)
    {
        // Handle the failures how you'd like.
    }

}
