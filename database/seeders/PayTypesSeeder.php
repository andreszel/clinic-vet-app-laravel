<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PayTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('pay_types')->insert([
            'name' => 'Ryczałt gotówka'
        ]);
        DB::table('pay_types')->insert([
            'name' => 'Ryczałt na koniec miesiąca'
        ]);
        DB::table('pay_types')->insert([
            'name' => 'Faktura VAT gotówka'
        ]);
        DB::table('pay_types')->insert([
            'name' => 'Faktura VAT przelew'
        ]);
    }
}
