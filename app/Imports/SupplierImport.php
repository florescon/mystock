<?php

declare(strict_types=1);

namespace App\Imports;

use App\Models\Supplier;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Validators\Failure;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\WithValidation;

class SupplierImport implements ToModel, WithHeadingRow, SkipsEmptyRows, SkipsOnFailure, WithValidation
{
    /**  */
    public function __construct()
    {
    }

    /**
     * @param  array $row
     *
     * @return \App\Models\Supplier
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        $supplier = new Supplier();

        if(isset($row['name']) && isset($row['phone'])){
            return $supplier->firstOrCreate(
                [
                    'name'       => $row['name']
                ],
                [
                    'phone'      => $row['phone'],
                    'email'    => $row['email'] ?? null,
                    'address'    => $row['address'] ?? null,
                    'city'    => $row['city'] ?? null,
                    'tax_number' => $row['tax_number'] ?? null,
                ]
            );

        }

    }

    public function rules(): array
    {
        return [
            'name' => [
                'max:100',
            ],
            'phone' => [
                'required',
                'numeric',
            ],
            'email' => [
                'email',
                'nullable',
            ],
            'address' => [
                'max:100',
            ],
            'city' => [
                'max:100',
            ],
            'tax_number' => [
                'numeric',
                'digits_between:1,100',
                'nullable',
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
