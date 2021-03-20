<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Patient;
use App\PatientRecord;
use App\PatientRecordProcedure;
use App\LookupTable;
use App\Procedure;

class ReportData extends Model {

    public static function earnings($from, $to) {
        $records = PatientRecord::whereBetween('created_at', [$from, $to])->where('cancelled', 0)->get()->toarray();

        $procedures = PatientRecordProcedure::whereBetween('created_at', [$from, $to])->where('disabled', 0)->get()->toarray();

        $lookup = Procedure::all()->toarray();

        return [
            'records' => $records,
            'procedures' => $procedures,
            'lookup' => $lookup
        ];
    }

    public static function getEarnings($from, $to) {
        $data = self::earnings($from, $to);

        $resultArray = [];

        list ('procedures' => $proceduresArray, 'lookup' => $lookupArray, 'records' => $recordsArray) = $data;

        
        foreach($proceduresArray as $value) {
            $id = $value['procedure_id'];

            $lookupDetails = self::findValue($lookupArray, $id);
            $recordDetails = self::findValue($recordsArray, $value['patient_record_id']);
            
            $discount_value = $recordDetails['discount_value'] / 100;
            $discount_amount = $discount_value ? $value['amount'] * $discount_value : 0;
            $newAmount = $value['amount'] - $discount_amount;
            
            $value['discount_value'] = $discount_value;
            $value['discount_amount'] = $discount_amount;
            $value['total'] = $newAmount;

            if (!in_array($id, array_column($resultArray, 'procedure_id'))) {
                $resultArray[$id] = [
                    'procedure_id' => $id,
                    'amount' => $newAmount,
                    'original' => $value['amount'],
                    'count' => 1,
                    'name' => $lookupDetails['name'],
                    'type' => $lookupDetails['procedure_type'],
                    'item' => [$value['id'] => $value]
                ];
                continue;
            } 
            
            $resultArray[$id]['amount'] += $newAmount;
            $resultArray[$id]['original'] += $value['amount'];
            $resultArray[$id]['count'] += 1;
            $resultArray[$id]['item'][$value['id']] = $value;
        }

        usort($resultArray, function($a, $b) {
            return $a['count'] >= $b['count'] ? -1 : 1;
        });

        return $resultArray;
    }

    static protected function findValue($array, $id, $key = 'id') {
        $key = array_search($id, array_column($array, $key));
        return $array[$key];
    }

    public static function patients($from, $to) {
        $records = PatientRecord::whereBetween('created_at', [$from, $to])->where('cancelled', 0)->get()->toarray();
        $patients = Patient::whereBetween('created_at', [$from, $to])->where('disabled', 0)->get()->toarray();

        $resultArray = [];

        $recordsArray = self::filterPatientRecords($records);
        $patientsArray = self::filterPatients($patients, $recordsArray);
        
        return $patientsArray;
    }

    public static function getPatients($from, $to) {
        return self::patients($from, $to);
    }

    public static function filterPatientRecords($data) {
        $resultArray = [];
        
        foreach($data as $value) {
            $id = $value['patient_id'];
            
            if (!in_array($id, array_column($resultArray, 'patient_id'))) {
                $resultArray[$id] = [
                    'patient_id' => $id,
                    'amount' => $value['total'],
                    'original' => $value['original_amount'],
                    'discount' => $value['discounted_amount'],
                    'count' => 1,
                    'item' => [$value['id'] => $value]
                ];
                continue;
            }
            
            $resultArray[$id]['amount'] += $value['total'];
            $resultArray[$id]['original'] += $value['original_amount'];
            $resultArray[$id]['discount'] += $value['discounted_amount'];
            $resultArray[$id]['count'] += 1;
            $resultArray[$id]['item'][$value['id']] = $value;
        }
        
        return $resultArray;
    }

    public static function filterPatients($patients, $records) {
        $old = [];
        $new = [];

        foreach($records as $value) {
            $id = $value['patient_id'];
            if (!in_array($id, array_column($patients, 'id'))) {
                $value['id'] = $id;
                $old[] = $value;
            }
        }


        foreach($patients as $value) {
            $id = $value['id'];
            
            if (!in_array($id, array_column($old, 'id'))) {
                $value['item'] = [];
                $value['count'] = 0;
                $value['amount'] = 0;
                $value['original'] = 0;
                $value['discount'] = 0;
                if (array_key_exists($id, $records)) {
                    $record = $records[$id];
                    $value['item'] = $record['item'];
                    $value['count'] = $record['count'];
                    $value['amount'] = $record['amount'];
                    $value['original'] = $record['original'];
                    $value['discount'] = $record['discount'];
                }

                $new[] = $value;
                continue;
            }
        }

        return [
            'new' => $new,
            'old' => $old
        ];
    }
}
