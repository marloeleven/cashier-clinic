<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;
use App\Variables\Procedures;
use App\ProcedureTypeCategory;

class ProceduresTableSeeder extends Seeder
{
    private function insert($procedures, $type) {
        foreach($procedures as $procedure) {
            DB::table('procedures')->insert([
                'procedure_type_categories_id' => $type,
                'name' => $procedure,
                'details' => "Laboratory {$procedure}",
                'amount' => rand(300, 1000),
                'created_at' => Carbon::now()->format('Y-m-d H:i:s')
            ]);
        }
    }
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categories = ProcedureTypeCategory::fetchAll()->toArray();

        $laboratory = ProcedureTypeCategory::getType($categories, Procedures::$types[0]);
        $radiology = ProcedureTypeCategory::getType($categories, Procedures::$types[1]);
        $consultation = ProcedureTypeCategory::getType($categories, Procedures::$types[2]);
        $send_in = ProcedureTypeCategory::getType($categories, Procedures::$types[3]);
        $corporate = ProcedureTypeCategory::getType($categories, Procedures::$types[4]);
        
        // LABORATORY

        $hematology = array_slice(Procedures::$laboratory, 0, 3);
        $this->insert($hematology, $laboratory[0]['id']);
        
        $bloodChem = array_slice(Procedures::$laboratory, 3, 3);
        $this->insert($bloodChem, $laboratory[1]['id']);

        $routine = array_slice(Procedures::$laboratory, 6, 3);
        $this->insert($routine, $laboratory[2]['id']);
        
        // RADIOLOGY
        
        $xRay = array_slice(Procedures::$radiology, 0, 1);
        $this->insert($xRay, $radiology[0]['id']);

        $ultraSound = array_slice(Procedures::$radiology, 1, 1);
        $this->insert($ultraSound, $radiology[1]['id']);

        $d2Echo = array_slice(Procedures::$radiology, 2, 1);
        $this->insert($d2Echo, $radiology[2]['id']);
        
        $ecg = array_slice(Procedures::$radiology, 3, 1);
        $this->insert($ecg, $radiology[3]['id']);

        // CONSULTATION
        
        $ape = array_slice(Procedures::$consultation, 0, 1);
        $this->insert($ape, $consultation[0]['id']);
        
        $consulFee = array_slice(Procedures::$consultation, 1, 2);
        $this->insert($consulFee, $consultation[1]['id']);
        
        $preEmp = array_slice(Procedures::$consultation, 3, 2);
        $this->insert($preEmp, $consultation[2]['id']);

        // SEND IN

        $hematology = array_slice(Procedures::$send_in, 0, 3);
        $this->insert($hematology, $send_in[0]['id']);
        
        $bloodChem = array_slice(Procedures::$send_in, 3, 3);
        $this->insert($bloodChem, $send_in[1]['id']);

        $routine = array_slice(Procedures::$send_in, 6, 3);
        $this->insert($routine, $send_in[2]['id']);

        // CORPORATE

        $ape = array_slice(Procedures::$corporate, 0, 3);
        $this->insert($ape, $corporate[0]['id']);
        
        $consulFee = array_slice(Procedures::$corporate, 3, 3);
        $this->insert($consulFee, $corporate[1]['id']);

        $preEmp = array_slice(Procedures::$corporate, 6, 3);
        $this->insert($preEmp, $corporate[2]['id']);
    }
}
