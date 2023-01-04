<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Plan;

class PlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Plan::create([
            'slug' => 'plan_mensual',
            'price' => 10000,
            'duration_in_days' => 30,
        ]);

        Plan::create([
            'slug' => 'plan_anual',
            'price' => 80000,
            'duration_in_days' => 365,
        ]);
    }
}
