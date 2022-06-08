<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class OfficeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //

        $data = [
            [
                'office' => 'N/A',
            ],
            [
                'office' => 'TANGUB CITY OFFICE',
            ],
            [
                'office' => 'CDO OFFICE',
            ],
        ];

        \App\Models\Office::insertOrIgnore($data);
    }
}
