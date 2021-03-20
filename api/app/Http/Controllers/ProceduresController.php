<?php
namespace App\Http\Controllers;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use App\Procedure;

class ProceduresController extends Controller {

    public function getAll() {
        return response()->json(Procedure::fetchAll());
    }

    public function getInfo(Request $request, $id) {
        return response()->json(Procedure::fetch($id));
    }

    public function create(Request $request) {
        $hasError = $this->validate($request, [
            'procedure_type_categories_id' => ['required', Rule::unique('procedures')->where(function ($query) use ($request) {
                $query->where('procedure_type_categories_id', $request->procedure_type_categories_id)
                        ->where('name', $request->name);
            })],
            'name' => 'required|min:2',
            'amount' => 'required',
            'sort' => 'required',
        ]);

        if($hasError) {
            $errors = $hasError->all();
            return response()->json([
                'message' => $errors[0],
                'errors'  => $errors
              ], 422);
        }

        $save = Procedure::create((array) $request->all());

        $save->load('procedure_type_category');

        return response()->json($save, 201);
    }

    public function update(Request $request, $id) {
        $hasError = $this->validate($request, [
            'procedure_type_categories_id' => ['required', Rule::unique('procedures')->where(function ($query) use ($request, $id) {
                $query->where('procedure_type_categories_id', $request->procedure_type_categories_id)
                        ->where('name', $request->name)
                        ->where('id', '!=', $id);
            })],
            'name' => 'required|min:2',
            'amount' => 'required'
        ]);
    
        if ($hasError) {
            return response()->json([
            'message' => 'Invalid Field(s)',
            'errors'  => $hasError->all()
            ], 422);
        }

        $procedure = Procedure::find($id);

        $procedure->procedure_type_categories_id = $request->procedure_type_categories_id;
        $procedure->name = $request->name;
        $procedure->details = $request->details;
        $procedure->amount = $request->amount;
        $procedure->sort = $request->sort;

        $procedure->save();

        return response()->json($procedure, 200);
    }

    public function delete(Request $request, $id) {
        $procedure = Procedure::find($id);

        $procedure->disabled = 1;
        $procedure->save();

        return response()->json($procedure, 200);
    }

    public function restore(Request $request, $id) {
        $procedure = Procedure::find($id);

        $procedure->disabled = 0;
        $procedure->save();

        return response()->json($procedure, 200);
    }
}
