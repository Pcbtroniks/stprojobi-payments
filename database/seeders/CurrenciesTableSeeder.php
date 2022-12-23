<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class CurrenciesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $currencies = [
            [
                'name' => 'US Dollar',
                'iso_code' => 'USD',
                'symbol' => '$',
            ],
            [
                'name' => 'Euro',
                'iso_code' => 'EUR',
                'symbol' => '€',
            ],
            [
                'name' => 'British Pound',
                'iso_code' => 'GBP',
                'symbol' => '£',
            ],
            [
                'name' => 'Mexican Peso',
                'iso_code' => 'MXN',
                'symbol' => '$',
            ],
        ];

        foreach ($currencies as $currency) {
            \App\Models\Currency::create($currency);
        }
    }
}
