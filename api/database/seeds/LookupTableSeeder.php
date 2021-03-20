<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class LookupTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('lookup_table')->insert([
            'type' => 'DISCOUNT',
            'name' => 'Christmas',
            'details' => 'Christmas Discount for all Patients',
            'amount' => 20,
            'index' => 1,
            'created_at' => Carbon::now()->format('Y-m-d H:i:s')
        ]);

        DB::table('lookup_table')->insert([
            'type' => 'DISCOUNT',
            'name' => 'Halloween',
            'details' => 'Holloween Discount for all Patients',
            'amount' => 15,
            'index' => 2,
            'created_at' => Carbon::now()->format('Y-m-d H:i:s')
        ]);

        DB::table('lookup_table')->insert([
            'type' => 'DISCOUNT',
            'name' => 'New Year',
            'details' => 'New Year Discount for all Patients',
            'amount' => 30,
            'index' => 3,
            'created_at' => Carbon::now()->format('Y-m-d H:i:s')
        ]);
        
        DB::table('lookup_table')->insert([
            'type' => 'ID_TYPE',
            'name' => 'SSS',
            'details' => 'Social Security System Identification Card',
            'index' => 1,
            'created_at' => Carbon::now()->format('Y-m-d H:i:s')
        ]);
        
        DB::table('lookup_table')->insert([
            'type' => 'ID_TYPE',
            'name' => 'Drivers License',
            'details' => 'LTO Drivers License Identification Card',
            'index' => 2,
            'created_at' => Carbon::now()->format('Y-m-d H:i:s')
        ]);
        
        DB::table('lookup_table')->insert([
            'type' => 'ID_TYPE',
            'name' => 'Company ID',
            'details' => 'Company Identification Card',
            'index' => 4,
            'created_at' => Carbon::now()->format('Y-m-d H:i:s')
        ]);
        
        DB::table('lookup_table')->insert([
            'type' => 'ID_TYPE',
            'name' => 'School ID',
            'details' => 'School Identification Card',
            'index' => 3,
            'created_at' => Carbon::now()->format('Y-m-d H:i:s')
        ]);
    }
}
