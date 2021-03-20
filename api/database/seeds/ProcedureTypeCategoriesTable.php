<?php

use Illuminate\Database\Seeder;
use App\Variables\Procedures;
use Carbon\Carbon;

class ProcedureTypeCategoriesTable extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $today = Carbon::now()->format('Y-m-d H:i:s');
        // LABORATORY
        DB::table('procedure_type_categories')->insert([
            'procedure_type' => Procedures::$types[0],
            'name' => 'Hematology',
            'alias' => 'Hema',
            'report_type' => 'cbc',
            'index' => 1,
            'created_at' => $today
        ]);

        DB::table('procedure_type_categories')->insert([
            'procedure_type' => Procedures::$types[0],
            'name' => 'Chemistry',
            'alias' => 'Chem',
            'report_type' => 'chemistry',
            'index' => 2,
            'created_at' => $today
        ]);

        DB::table('procedure_type_categories')->insert([
            'procedure_type' => Procedures::$types[0],
            'name' => 'Routine',
            'alias' => 'Routine',
            'report_type' => 'generic',
            'index' => 3,
            'created_at' => $today
        ]);


        // RADIOLOGY
        DB::table('procedure_type_categories')->insert([
            'procedure_type' => Procedures::$types[1],
            'name' => 'X-RAY',
            'alias' => 'X-Ray',
            'report_type' => 'generic',
            'index' => 1,
            'created_at' => $today
        ]);

        DB::table('procedure_type_categories')->insert([
            'procedure_type' => Procedures::$types[1],
            'name' => 'Ultrasound',
            'alias' => 'Ultrasound',
            'report_type' => 'generic',
            'index' => 2,
            'created_at' => $today
        ]);

        DB::table('procedure_type_categories')->insert([
            'procedure_type' => Procedures::$types[1],
            'name' => '2D Echo',
            'alias' => '2D Echo',
            'report_type' => 'generic',
            'index' => 3,
            'created_at' => $today
        ]);

        DB::table('procedure_type_categories')->insert([
            'procedure_type' => Procedures::$types[1],
            'name' => 'ECG',
            'alias' => 'ECG',
            'report_type' => 'ecg',
            'index' => 4,
            'created_at' => $today
        ]);

        // CONSULTATION
        DB::table('procedure_type_categories')->insert([
            'procedure_type' => Procedures::$types[2],
            'name' => 'Annual Physical Examination',
            'alias' => 'APE',
            'report_type' => 'generic',
            'index' => 1,
            'created_at' => $today
        ]);

        DB::table('procedure_type_categories')->insert([
            'procedure_type' => Procedures::$types[2],
            'name' => 'Consultation Fee',
            'alias' => 'Con Fee',
            'report_type' => 'generic',
            'index' => 2,
            'created_at' => $today
        ]);

        DB::table('procedure_type_categories')->insert([
            'procedure_type' => Procedures::$types[2],
            'name' => 'Pre-Employment',
            'alias' => 'Pre Emp',
            'report_type' => 'generic',
            'index' => 3,
            'created_at' => $today
        ]);

        // SEND IN
        DB::table('procedure_type_categories')->insert([
            'procedure_type' => Procedures::$types[3],
            'name' => 'Hematology',
            'alias' => 'Hema',
            'report_type' => 'cbc',
            'index' => 1,
            'created_at' => $today
        ]);

        DB::table('procedure_type_categories')->insert([
            'procedure_type' => Procedures::$types[3],
            'name' => 'Chemistry',
            'alias' => 'Chem',
            'report_type' => 'chemistry',
            'index' => 2,
            'created_at' => $today
        ]);

        DB::table('procedure_type_categories')->insert([
            'procedure_type' => Procedures::$types[3],
            'name' => 'Routine',
            'alias' => 'Routine',
            'report_type' => 'generic',
            'index' => 3,
            'created_at' => $today
        ]);



        // CORPORATE
        DB::table('procedure_type_categories')->insert([
            'procedure_type' => Procedures::$types[4],
            'name' => 'Hematology',
            'alias' => 'Hema',
            'report_type' => 'cbc',
            'index' => 1,
            'created_at' => $today
        ]);

        DB::table('procedure_type_categories')->insert([
            'procedure_type' => Procedures::$types[4],
            'name' => 'Chemistry',
            'alias' => 'Chem',
            'report_type' => 'chemistry',
            'index' => 2,
            'created_at' => $today
        ]);

        DB::table('procedure_type_categories')->insert([
            'procedure_type' => Procedures::$types[4],
            'name' => 'Routine',
            'alias' => 'Routine',
            'report_type' => 'generic',
            'index' => 3,
            'created_at' => $today
        ]);

        DB::table('procedure_type_categories')->insert([
            'procedure_type' => Procedures::$types[4],
            'name' => 'Annual Physical Examination',
            'alias' => 'APE',
            'report_type' => 'generic',
            'index' => 4,
            'created_at' => $today
        ]);

        DB::table('procedure_type_categories')->insert([
            'procedure_type' => Procedures::$types[4],
            'name' => 'Consultation Fee',
            'alias' => 'Con Fee',
            'report_type' => 'generic',
            'index' => 5,
            'created_at' => $today
        ]);

        DB::table('procedure_type_categories')->insert([
            'procedure_type' => Procedures::$types[4],
            'name' => 'Pre-Employment',
            'alias' => 'Pre Emp',
            'report_type' => 'generic',
            'index' => 6,
            'created_at' => $today
        ]);
    }
}
