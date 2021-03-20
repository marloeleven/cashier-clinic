<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;
use App\Procedure;

class PatientRecordProceduresTable extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
       

        for ($i = 1; $i < 37; $i++) {
            DB::table('patient_record_procedures')->insert([
                'patient_record_id' => 1,
                'procedure_id' => $i,
                'amount' => rand(500, 1200),
                'created_at' => Carbon::now()->format('Y-m-d H:i:s')
            ]);
        }

        // for ($i = 0; $i < 50; $i++) {
        //     DB::table('patient_record_procedures')->insert([
        //         'patient_record_id' => 1,
        //         'procedure_id' => rand(1, 36),
        //         'amount' => rand(500, 1200),
        //         'created_at' => Carbon::now()->format('Y-m-d H:i:s')
        //     ]);
        // }

        for ($i = 0; $i < 40; $i++) {
            DB::table('patient_record_procedures')->insert([
                'patient_record_id' => 1,
                'procedure_id' => rand(10, 16),
                'amount' => rand(500, 1200),
                'created_at' => Carbon::now()->format('Y-m-d H:i:s')
            ]);
        }
    }
}
