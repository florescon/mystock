<?php

declare(strict_types=1);

namespace App\Imports;

use App\Models\Customer;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Validators\Failure;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\WithValidation;

class CustomerImport implements ToModel, WithHeadingRow, SkipsEmptyRows, SkipsOnFailure, WithValidation
{
    /**  */
    public function __construct()
    {
    }

    /**
     * @param  array $row
     *
     * @return \App\Models\Customer
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        $customer = new Customer();

        if(isset($row['name']) && isset($row['phone'])){
            return $customer->firstOrCreate([
                'name'  => $row['name'],
                'phone' => $row['phone'],
                'email' => $row['email'] ?? null,
                'address' => $row['address'] ?? null,
                'city' => $row['city'] ?? null,
                'tax_number' => $row['tax_number'] ?? null,
            ]);
        }
    }

    public function rules(): array
    {
        return [
            'name' => [
                'required',
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
                'nullable',
            ],
            'city' => [
                'max:100',
                'nullable',
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
