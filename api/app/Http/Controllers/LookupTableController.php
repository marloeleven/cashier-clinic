<?php
namespace App\Http\Controllers;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use App\LookupTable;

class LookupTableController extends Controller {

    public function getAll() {
        return response()->json(LookupTable::all(), 200);
    }

    public function getAllByType(Request $request, $type) {
        return response()->json(LookupTable::where('type', $type)->get(), 200);
    }

    public function getInfo(Request $request, $id) {
        return response()->json(LookupTable::find($id), 200);
    }

    public function create(Request $request) {        
        $hasError = $this->validate($request, [
            'type' => ['required', Rule::unique('lookup_table')->where(function ($query) use ($request) {
                $query->where('type', $request->type)
                        ->where('name', $request->name);
            })],
            'name' => 'required'
        ]);
    
        if ($hasError) {
            return response()->json([
            'message' => 'Invalid Field(s)',
            'errors'  => $hasError->all()
            ], 422);
        }

        $lookup = LookupTable::create((array) $request->all());

        return response()->json($lookup, 201);
    }

    public function update(Request $request, $id) {
        $hasError = $this->validate($request, [
            'type' => ['required', Rule::unique('lookup_table')->where(function ($query) use ($request, $id) {
                $query->where('type', $request->type)
                        ->where('name', $request->name)
                        ->where('id', '!=', $id);
            })],
            'name' => 'required'
        ]);
    
        if ($hasError) {
            return response()->json([
            'message' => 'Invalid Field(s)',
            'errors'  => $hasError->all()
            ], 422);
        }

        $lookup = LookupTable::find($id);

        $lookup->type = $request->type;
        $lookup->name = $request->name;
        $lookup->details = $request->details;
        $lookup->amount = $request->amount;

        $lookup->save();

        return response()->json($lookup, 200);
    }

    
    public function delete(Request $request, $id) {
        $lookup = LookupTable::find($id);

        $lookup->disabled = 1;
        $lookup->save();

        return response()->json($lookup, 200);
    }

    public function restore(Request $request, $id) {
        $lookup = LookupTable::find($id);

        $lookup->disabled = 0;
        $lookup->save();

        return response()->json($lookup, 200);
    }
}
