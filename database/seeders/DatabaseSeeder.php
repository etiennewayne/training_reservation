<?php

namespace Database\Seeders;

use App\Models\AppointmentType;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        $this->call([
            OfficeSeeder::class,
            UserSeeder::class,
            AppointmentTypeSeeder::class,
            AppClockSeeder::class,
            HealthQuestionSeeder::class,
        ]);
    }
}
