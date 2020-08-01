<?php

namespace App\Imports;

use App\FileContent;
use App\User;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;

class ExcelImport implements ToModel
{

    public function model(array $row)
    {  //'file', 'name','email','phone','age','salary','phonetype','feedback'
        return new FileContent([

            'name'    => $row[0],
            'email'    => $row[1],
            'phone'    => $row[2],
            'age'    => $row[3],
            'salary'    => $row[4],
            'phone_type'    => $row[5],

        ]);
    }
}
