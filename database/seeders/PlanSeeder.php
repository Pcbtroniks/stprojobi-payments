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
            'price' => 9900,
            'duration_in_days' => 30,
        ]);

        Plan::create([
            'slug' => 'plan_semestral',
            'price' => 49900,
            'duration_in_days' => 180,
        ]);

        Plan::create([
            'slug' => 'plan_anual',
            'price' => 89900,
            'duration_in_days' => 365,
        ]);
    }
}
