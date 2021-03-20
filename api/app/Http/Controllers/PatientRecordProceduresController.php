<?php
namespace App\Http\Controllers;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use App\PatientRecordProcedure;

class PatientRecordProceduresController extends Controller {

    public function getInfo(Request $request, $id) {
        $procedure = PatientRecordProcedure::with(['procedure_type'])->find($id);

        $procedure['procedure'] = $procedure->procedure;

        return response()->json($procedure, 200);
    }
}
