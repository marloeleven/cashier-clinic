<?php
namespace App\Http\Controllers;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use App\ProcedureTypeCategory;

class ProcedureTypeCategoryController extends Controller {

    public function getAll() {
        return response()->json(ProcedureTypeCategory::fetchAll());
    }

    

    public static function forListing() {
        return response()->json(ProcedureTypeCategory::selection()->with(['procedures'])->where('disabled', 0)->orderBy('index', 'ASC')->get()->groupBy('procedure_type'));
    }

    public function getInfo(Request $request, $id) {
        return response()->json(ProcedureTypeCategory::fetch($id));
    }

    public function create(Request $request) {
        $hasError = $this->validate($request, [
            'procedure_type' => ['required', Rule::unique('procedure_type_categories')->where(function ($query) use ($request) {
                $query->where('procedure_type', $request->procedure_type)
                        ->where('name', $request->name);
            })],
            'name' => 'required|min:2',
            'alias' => 'required|min:2'
        ]);

        if($hasError) {
            $errors = $hasError->all();
            return response()->json([
                'message' => $errors[0],
                'errors'  => $errors
              ], 422);
        }

        $save = ProcedureTypeCategory::create((array) $request->all());

        return response()->json($save, 201);
    }

    public function update(Request $request, $id) {
        $hasError = $this->validate($request, [
            'procedure_type' => ['required', Rule::unique('procedure_type_categories')->where(function ($query) use ($request, $id) {
                $query->where('procedure_type', $request->procedure_type)
                        ->where('name', $request->name)
                        ->where('id', '!=', $id);
            })],
            'name' => 'required|min:2',
            'alias' => 'required|min:2'
        ]);
    
        if ($hasError) {
            return response()->json([
            'message' => 'Invalid Field(s)',
            'errors'  => $hasError->all()
            ], 422);
        }

        $procedure = ProcedureTypeCategory::find($id);

        $procedure->procedure_type = $request->procedure_type;
        $procedure->name = $request->name;
        $procedure->alias = $request->alias;
        $procedure->report_type = $request->report_type;
        $procedure->index = $request->index;

        $procedure->save();

        return response()->json($procedure, 200);
    }

    public function delete(Request $request, $id) {
        $procedure = ProcedureTypeCategory::find($id);

        $procedure->disabled = 1;
        $procedure->save();

        return response()->json($procedure, 200);
    }

    public function restore(Request $request, $id) {
        $procedure = ProcedureTypeCategory::find($id);

        $procedure->disabled = 0;
        $procedure->save();

        return response()->json($procedure, 200);
    }
}
