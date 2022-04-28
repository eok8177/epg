<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

use App\Models\User;

use App\Library\ExportXML;

class ClientController extends Controller
{
    public function update(Request $request)
    {
        $user = $this->auth($request);

        $user->password = Hash::make($request->get('password'));
        $user->save();

        return response()->json(['status'=>'OK'], 200);
    }



    public function exportAllChanels(Request $request)
    {
        $user = $this->auth($request);

        return ExportXML::exportAllXML($user);
    }




    private function auth($request)
    {
        $token = $request->header('Authorization');
        $id = $request->header('Client');
        $client = User::where('id',$id)
            ->where('token',$token)
            ->first();

        if (!$client) {
            exit(); //404
        }
        return $client;
    }

}
