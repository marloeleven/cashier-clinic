<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Announcement;

class AnnouncementsController extends Controller {
    
    public function getAll() {
        return response()->json(Announcement::orderBy('created_at', 'asc')->get(), 200);
    }

    public function getInfo(Request $request, $id) {
        return response()->json(Announcement::find($id), 200);
    }

    public function create(Request $request) {        
        $hasError = $this->validate($request, [
            'title' => 'required',
            'body' => 'required'
        ]);
    
        if ($hasError) {
            return response()->json([
            'message' => 'Invalid Field(s)',
            'errors'  => $hasError->all()
            ], 422);
        }

        $announcement = Announcement::create((array) $request->all());

        return response()->json($announcement, 201);
    }

    public function update(Request $request, $id) {
        $hasError = $this->validate($request, [
            'title' => 'required',
            'body' => 'required'
        ]);
    
        if ($hasError) {
            return response()->json([
            'message' => 'Invalid Field(s)',
            'errors'  => $hasError->all()
            ], 422);
        }

        $announcement = Announcement::find($id);

        $announcement->title = $request->title;
        $announcement->body = $request->body;

        $announcement->save();

        return response()->json($announcement, 200);
    }

    
    public function delete(Request $request, $id) {
        $announcement = Announcement::find($id);

        $announcement->disabled = 1;
        $announcement->save();

        return response()->json($announcement, 200);
    }

    public function restore(Request $request, $id) {
        $announcement = Announcement::find($id);

        $announcement->disabled = 0;
        $announcement->save();

        return response()->json($announcement, 200);
    }

}
