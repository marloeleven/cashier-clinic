<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Helpers\Compute;
use App\PatientRecordProcedure;
use App\PatientRecord;
use App\LookupTable;
use App\Procedure;
use App\Patient;
use DB;

class PatientRecordsController extends Controller {

    public function getAll(Request $request, $patientId) {
        return response()->json(PatientRecord::fetchAll($patientId), 200);
    }

    public function getAllDoctors() {
        return  response()->json(PatientRecord::select('attending_physician')->whereCancelled(0)->orderBy('attending_physician', 'ASC')->groupBy('attending_physician')->get(), 200);
    }

    public function create(Request $request) {
        $hasError = $this->validate($request, [
            'patient_id' => 'required',
            'reference_number' => ['required', Rule::unique('patient_records')->where(function ($query) use ($request) {
                $query->whereCancelled(0);
            })],
            'procedures' => 'required'
        ]);
    
        if ($hasError) {
            return response()->json([
            'message' => 'Invalid Field(s)',
            'errors'  => $hasError->all()
            ], 422);
        }

        $discount_percentage = 0;

        $discount_type_lookup_table_id = $request->input('discount_type_lookup_table_id', 0);
        
        if ($discount_type_lookup_table_id) {
            $discount_percentage = LookupTable::find($discount_type_lookup_table_id)->amount;
        }

        $procedures = Procedure::with(['procedure_type_category'])->whereIn('id', $request->procedures)->get();
        $patient = Patient::find($request->patient_id);
        $isSenior = Patient::isSenior($patient->birth_date);

        $senior_citizen_discount = 0;
        if ($isSenior) {
            $senior_citizen_discount = $request->get('senior_citizen_discount', 0);
        }
        
        $compute = Compute::compute($procedures, $discount_percentage / 100, $senior_citizen_discount); 

        $record = new PatientRecord();
        $record->patient_id = $request->patient_id;
        $record->reference_number = $request->reference_number;
        $record->cashier_id = $request->user()->id;
        $record->discount_type_lookup_table_id = $discount_type_lookup_table_id;
        $record->discount_value = $discount_percentage;
        $record->original_amount = $compute->original_amount;
        $record->discounted_amount = $compute->discounted_amount;
        $record->total = $compute->total_amount;
        $record->attending_physician = $request->attending_physician;
        $record->comment = $request->comment;
        $record->senior_citizen_discount = $senior_citizen_discount;

        $record->save();

        $procedures->each(function($procedure) use ($record) {
            $instance = new PatientRecordProcedure();
            $instance->patient_record_id = $record->id;
            $instance->procedure_id = $procedure->id;
            $instance->amount = $procedure->amount;

            $instance->save();
        });

        return response()->json($record, 201);
    }

    public function delete(Request $request, $id) {
        $record = PatientRecord::find($id);
        $record->cancelled = 1;
        $record->cancelled_by_id = $request->user()->id;

        $record->save();

        $procedures = PatientRecordProcedure::where('patient_record_id', $record->id)->update(['disabled' => 1]);

        return response()->json($record, 200);
    }

    public function restore(Request $request, $id) {
        $record = PatientRecord::find($id);

        $check = PatientRecord::where('reference_number', $record->reference_number)->whereCancelled(0)->first();

        if ($check) {
            return response()->json(['message' => 'Invalid Field(s)', 'errors' => ['The reference number has already been taken.']], 422);
        }

        $record->cancelled = 0;
        $record->cancelled_by_id = '';

        $record->save();

        $procedures = PatientRecordProcedure::where('patient_record_id', $record->id)->update(['disabled' => 0]);

        return response()->json($record, 200);
    }

    public function getProcedures(Request $request, $id) {
        $record = PatientRecord::getProcedures($id);

        return response()->json($record, 200);
    }
}
