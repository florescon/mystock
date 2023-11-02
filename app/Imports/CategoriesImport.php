<?php

declare(strict_types=1);

namespace App\Imports;

use App\Models\Category;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class CategoriesImport implements ToModel, WithHeadingRow, SkipsEmptyRows
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

        if(isset($row['name'])){
            return $cat->firstOrCreate([
                'code' => $row['code'] ?? Str::random(5)],
                ['name' => $row['name'],
            ]);
        }
    }
}
