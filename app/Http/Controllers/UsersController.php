<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

use App\Models\User;

class UsersController extends Controller
{
    /**
     * Handle an authentication attempt.
     *
     * @param  \Illuminate\Http\Request $request
     *
     * @return Response
     */

    public function new_registration(Request $req) {
        $new_data = array(
            'name' => $req->input('name'),
            'email' => $req->input('email'),
            'password' => $req->input('password')
        );
        print_r($new_data);die;
    }
    public function register(Request $request)
    {
        $validator = $request->validate([
            'data.fullname' => 'required',
            'data.email' => 'required|email|unique:users,email',
            'data.password' => 'required',
        ]);
        
        $user = new User;
        $user->name = $request->input('data.fullname');
        $user->email = $request->input('data.email');
        $user->password = Hash::make($request->input('data.password'));
        $user->role = '1';
        $user->client_id = $request->input('data.client');
        print_r($user);die;
        //$user->language = $request->input('data.language');
        if($user->save()) {
            return response()->json(['status'=>'1','message' => 'Successfully registered'], 200);
        } else {
            return response()->json(['status'=>'0','message' => 'User has not been registered'], 422);
        }
    }

    public function users() {
        $users = User::select('users.id', 'users.name', 'users.email', 'users.client_id', 'users.language')->with('client')->where('role', '<>', '0')->get();
        return response()->json(['status'=>'1','message' => 'User List', 'users' => $users], 200);
    }

    public function logout(Request $request) {
        $request->user()->currentAccessToken()->delete();
        $request->user()->tokens()->delete();
        return response()->json(['status'=>'1','message' => 'Successfully Logout'], 200);
    }

    public function change_password(Request $request) {
        $validator = $request->validate([
            'data.current_password' => 'required',
            'data.password' => 'required| min:4 | max:12 |confirmed',
            'data.password_confirmation' => 'required| min:4'
        ]);
        $user = $request->user();
        if (Hash::check($request->input('data.current_password'), $user->password)) {
            $user->password = Hash::make($request->input('data.password'));
            $user->save();
            return response()->json(['status'=>'1','message' => 'Successfully Password Changed'], 200);
        } else {
            return response()->json(['status'=>'0','message' => 'Old Password does not match'], 422);
        }
    }

    public function getuser($id) {
        $user = User::where('id', '=', $id)->first();
        return response()->json(['status'=>'1','message' => 'User Information', 'user' => $user], 200);
    }

    public function edit_user(Request $request)
    {
        $validator = $request->validate([
            'data.fullname' => 'required',
            'data.email' => 'required|email',
        ]);
        
        $user = User::find($request->input('edit_id'));
        $user->name = $request->input('data.fullname');
        $user->email = $request->input('data.email');
        if(!empty($request->input('data.password'))) {
            $user->password = Hash::make($request->input('data.password'));
        }
        $user->client_id = $request->input('data.client');
        //$user->language = $request->input('data.language');
        if($user->save()) {
            return response()->json(['status'=>'1','message' => 'Successfully User updated.'], 200);
        } else {
            return response()->json(['status'=>'0','message' => 'Error in User update'], 422);
        }
    } 

    public function deleteUser ($id) {
        User::where('id', '=', $id)->delete();
        return response()->json(['status'=>'1','message' => 'User Deleted SuccessFully'], 200);
    }


}