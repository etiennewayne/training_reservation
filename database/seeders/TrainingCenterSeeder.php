<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class TrainingCenterSeeder extends Seeder
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
                'training_center' => 'TANGUB CITY TRAINING CENTER',
                'address' => 'TANGUB CITY'
            ],
            [
                'training_center' => 'CDO TRAINING CENTER',
                'address' => 'CAGAYAN DE ORO CITY'
            ],

        ];

        \App\Models\TraningCenter::insertOrIgnore($data);
    }
}
