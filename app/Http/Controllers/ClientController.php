<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

use App\Models\Client;

class ClientController extends Controller
{
    /**
     * Handle an authentication attempt.
     *
     * @param  \Illuminate\Http\Request $request
     *
     * @return Response
     */
    public function add_client(Request $request)
    {
        $validator = $request->validate([
            'data.name' => 'required',
            'data.email' => 'required|email|unique:clients,email',
            'data.description' => 'required',
        ]);
        
        $client = new Client;
        $client->name = $request->input('data.name');
        $client->email = $request->input('data.email');
        $client->description = $request->input('data.description');
        if($client->save()) {
            return response()->json(['status'=>'1','message' => 'Successfully client added.'], 200);
        } else {
            return response()->json(['status'=>'0','message' => 'Error in client adding'], 422);
        }
    }

    public function clients() {
        $clients = Client::select('id', 'name', 'email', 'description')->where('status', '=', '1')->get();
        return response()->json(['status'=>'1','message' => 'Client List', 'clients' => $clients], 200);
    }

    public function getclient($id) {
        $client = Client::select('id', 'name', 'email', 'description')->where('id', '=', $id)->first();
        return response()->json(['status'=>'1','message' => 'Client Information', 'client' => $client], 200);
    }

    public function edit_client(Request $request)
    {
        $validator = $request->validate([
            'data.name' => 'required',
            'data.email' => 'required|email',
            'data.description' => 'required',
        ]);
        
        $client = Client::find($request->input('edit_id'));
        $client->name = $request->input('data.name');
        $client->email = $request->input('data.email');
        $client->description = $request->input('data.description');
        if($client->save()) {
            return response()->json(['status'=>'1','message' => 'Successfully client updated.'], 200);
        } else {
            return response()->json(['status'=>'0','message' => 'Error in client update'], 422);
        }
    }
    
    public function deleteClient($id){
        Client::where('id', '=', $id)->delete();
        return response()->json(['status'=>'1','message' => 'Client deleted Successfully'], 200);
    }

}