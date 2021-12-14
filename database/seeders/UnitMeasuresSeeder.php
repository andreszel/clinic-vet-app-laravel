<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UnitMeasuresSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('unit_measures')->insert([
            'name' => 'sztuka',
            'short_name' => 'szt.'
        ]);
        DB::table('unit_measures')->insert([
            'name' => 'mililitr',
            'short_name' => 'ml.'
        ]);
    }
}
