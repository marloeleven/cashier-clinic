<?php
namespace App\Http\Controllers;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use App\Patient;

class PatientsController extends Controller {

    public function getAll(Request $request) {
        
        $searchFor = $request->input('search', '');
        $page = $request->input('page', 1);
        $limit = $request->input('limit', 25);
        $sortBy = $request->input('sortBy', '');
        $direction = $request->input('direction', 'asc');
        $delete = $request->input('deleted');
        
        return response()->json(
            Patient::fetchAll($searchFor, $page, $limit, $delete, $sortBy, $direction)
        );
    }

    public function getInfo(Request $request, $id) {
        return response()->json(Patient::fetch($id));
    }

    public function create(Request $request) {
        $hasError = $this->validate($request, [
            'first_name' => ['required', 'min:2', Rule::unique('patients')->where(function ($query) use ($request) {
                $query->where('first_name', $request->first_name)
                        ->where('middle_name', $request->middle_name)
                        ->where('last_name', $request->last_name);
            })],
            'last_name' => 'required',
            'gender' => 'required',
            'birth_date' => 'required|date'
        ]);

        if($hasError) {
            $errors = $hasError->all();
            return response()->json([
                'message' => $errors[0],
                'errors'  => $errors
              ], 422);
        }

        $save = Patient::create((array) $request->all());

        return response()->json($save, 201);
    }

    public function update(Request $request, $id) {
        $hasError = $this->validate($request, [
            'first_name' => 'required',
            'last_name' => 'required',
            'gender' => 'required',
            'birth_date' => 'required|date'
        ]);

        if($hasError) {
            $errors = $hasError->all();
            return response()->json([
                'message' => $errors[0],
                'errors'  => $errors
              ], 422);
        }

        $patient = Patient::find($id);
        $patient->first_name = $request->input('first_name');
        $patient->middle_name = $request->input('middle_name');
        $patient->last_name = $request->input('last_name');
        $patient->contact_number = $request->input('contact_number');
        $patient->gender = $request->input('gender');
        $patient->birth_date = $request->input('birth_date');
        $patient->idc_type_lookup_table_id = $request->input('idc_type_lookup_table_id');
        $patient->idc_number = $request->input('idc_number');

        $patient->save();

        return response()->json($patient, 200);
    }

    public function delete(Request $request, $id) {
      
      $patient = Patient::find($id);
  
      $patient->disabled = 1;
      $patient->save();
  
      return response()->json($patient, 200);
    }

    public function restore(Request $request, $id) {
      
        $patient = Patient::find($id);
    
        $patient->disabled = 0;
        $patient->save();
    
        return response()->json($patient, 200);
      }

}
