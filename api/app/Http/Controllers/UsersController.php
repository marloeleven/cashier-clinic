<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use App\User;
use DB;

class UsersController extends Controller {

  public function login(Request $request) {
    
    $hasError = $this->validate($request, [
      'username' => 'required|min:2',
      'password' => 'required:min:5'
    ]);

    if ($hasError) {
      return response()->json([
        'message' => 'Invalid Field(s)',
        'errors'  => $hasError->all()
      ], 422);
    }
      
    $user = User::select([
                  'id',
                  'username',
                  'password',
                  'email',
                  'first_name',
                  'middle_name',
                  'last_name',
                  'gender',
                  'picture',
                  'type',
                  'token',
                  'token_expiration'
                ])->where('username', $request->username)->first();
    if ($user) {
      $result = Hash::check($request->password, $user->password);
      
      if ($result) {
        $token = base64_encode(str_random(40));
        
        $user->token = $token;
        $user->token_expiration = Carbon::now()->addDay()->toDateTimeString();
        $user->save();
        
        unset($user['password']);
        
        return response()->json($user);
      }
      
      return response()->json([
        'message' => 'Incorrect Password!',
        'errors'  => ['Incorrect Password!']
      ], 400);
          
    }
    
    return response()->json([
      'message' => 'Incorrect Username!',
      'errors'  => ['Incorrect Username!']
    ], 400);
  }

  public function logout(Request $request) {
    $user = User::fetch($id);
    $user->token = '';
    $user->save();

    return response()->json([ success => 1 ]);
  }

  public function getAll() {
    return response()->json(User::fetchAll());
  }

  public function getInfo(Request $request, $id) {
    return response()->json(User::fetch($id));
  }

  public function getAllCashier() {
    return response()->json(User::whereType('CASHIER')->whereDisabled(0)->get());
  }

  public function create(Request $request) {

    $hasError = $this->validate($request, [
      'username' => 'required|min:2|unique:users',
      'type' => 'required',
      'first_name' => 'required',
      'last_name' => 'required',
      'gender' => 'required'
    ]);

    if ($hasError) {
      return response()->json([
        'message' => 'Invalid Field(s)',
        'errors'  => $hasError->all()
      ], 422);
    }

    $user = new User();
    $user->username = $request->input('username');
    $user->password = Hash::make('password');
    $user->type = $request->input('type');
    $user->first_name = $request->input('first_name');
    $user->middle_name = $request->input('middle_name');
    $user->last_name = $request->input('last_name');
    $user->gender = $request->input('gender');
    $user->email = $request->input('email');

    $user->save();

    return response()->json($user, 201);
  }

  public function update(Request $request, $id) {

    $hasError = $this->validate($request, [
      'username' => "required|min:2|unique:users,id,{$id}",
      'type' => 'required',
      'first_name' => 'required',
      'last_name' => 'required',
      'gender' => 'required'
    ]);

    if ($hasError) {
      return response()->json([
        'message' => 'Invalid Field(s)',
        'errors'  => $hasError->all()
      ], 422);
    }

    $user = User::find($id);
    $user->username = $request->input('username');
    $user->password = Hash::make($request->input('password'));
    $user->type = $request->input('type');
    $user->first_name = $request->input('first_name');
    $user->middle_name = $request->input('middle_name');
    $user->last_name = $request->input('last_name');
    $user->gender = $request->input('gender');
    $user->email = $request->input('email');

    $user->save();

    return response()->json($user, 200);
  }

  public function updateRecord(Request $request, $id) {

    $hasError = $this->validate($request, [
      'username' => "required|min:2|unique:users,id,{$id}",
      'type' => 'required',
      'first_name' => 'required',
      'last_name' => 'required',
      'gender' => 'required',
    ]);

    if ($hasError) {
      return response()->json([
        'message' => 'Invalid Field(s)',
        'errors'  => $hasError->all()
      ], 422);
    }

    $user = User::find($id);
    $user->username = $request->input('username');
    $user->type = $request->input('type');
    $user->first_name = $request->input('first_name');
    $user->middle_name = $request->input('middle_name');
    $user->last_name = $request->input('last_name');
    $user->gender = $request->input('gender');
    $user->email = $request->input('email');

    $user->save();

    return response()->json($user, 200);
  }

  public function delete(Request $request, $id) {
    
    $user = User::find($id);

    $user->disabled = 1;
    $user->save();

    return response()->json($user, 200);
  }

  public function restore(Request $request, $id) {
    
    $user = User::find($id);

    $user->disabled = 0;
    $user->save();

    return response()->json($user, 200);
  }

  // PASSWORD UPDATE
  public function passwordUpdate(Request $request) {
    $user = User::find($request->user()->id);

    if (!Hash::check($request->get('password'), $user->password)) {
      return response()->json(['message' => 'Incorrect Password'], 422);
    }

    $user->password = Hash::make($request->get('newPassword'));
    $user->save();

    return response()->json(['message' => 'Password updated successfully!'],200);
  }
}
