<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

use Mail;
use DB;

use Illuminate\Validation\ValidationException;

use App\Models\User;

class LoginController extends Controller
{
    /**
     * Handle an authentication attempt.
     *
     * @param  \Illuminate\Http\Request $request
     *
     * @return Response
     */
    public function authenticate(Request $request)
    {
        $request->validate([
            'username' => 'required|email',
            'password' => 'required',
            'device_name' => 'required',
        ]);
    
        $user = User::where('email', $request->username)->with('client')->first();
    
        if (! $user || ! Hash::check($request->password, $user->password)) {
            // throw ValidationException::withMessages([
            //     'error' => 'Incorrect Username or password. Logon denied.',
            // ]);
            return response()->json(['status' => '0', 'message' => 'Incorrect Username or password. Logon denied.'], 422);
        }
        $dataResponse = ['token' =>  $user->createToken($request->device_name)->plainTextToken, 'id' => $user->id, 'name' => $user->name, 'email' => $user->email, 'role' => $user->role, 'client_id' => $user->client_id, 'client_name' => $user->client ? $user->client->name : ''];
        return response()->json(['status' => '1', 'message' => 'Successfully Login', 'user' => $dataResponse ], 200);
    }

    public function forget_password(Request $request)
    {
        
        $request->validate([
            'data.registerUsername' => 'required|email',
        ]);
    
        $user = User::where('email', $request->input('data.registerUsername'))->first();
    
        if (!$user) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
            return response()->json(['status' => '0', 'message' => 'The provided credentials are incorrect'], 422);
        } else {
            $email = $request->input('data.registerUsername');
            $data = ['name' => $user->name, 'token' => base64_encode($user->id)];
            Mail::send('forget-password', $data , function($message) use ($email) {
                $message->to($email)
                ->from('adsininternet0@gmail.com', 'Digit')
                ->subject("Forget Password");
                });
            return response()->json(['status' => '1', 'message' => 'Password Reset Email Send Successfully', 'url' => "http://localhost:4300/reset-password/".base64_encode($user->id)], 200);    
        }
        
    }

    public function reset_password(Request $request) {
        $validator = $request->validate([
            'data.password' => 'required| min:4 | max:12 |confirmed',
            'data.password_confirmation' => 'required| min:4'
        ]);
        $id = base64_decode($request->input('token')); 
        $user = User::find($id);
        $user->password = Hash::make($request->input('data.password'));
        $user->save();
        return response()->json(['status'=>'1','message' => 'Successfully Password Reset'], 200);
    }
    

}