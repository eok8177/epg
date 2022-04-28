<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Chanel;
use App\Models\Program;

use App\Http\Resources\ChanelsResource;
use App\Http\Resources\ChanelResource;
use App\Http\Resources\ProgramResource;

use App\Library\ExportXML;

class ChanelController extends Controller
{
    public function chanels(Request $request)
    {
        $user = $this->auth($request);

        $chanels = Chanel::where('user_id',$user->id)->get();

        return response()->json(new ChanelsResource($chanels), 200);
    }

    public function chanel(Request $request, Chanel $chanel)
    {
        $user = $this->auth($request);
        if ($chanel->user_id != $user->id) abort('404');

        return response()->json(new ChanelResource($chanel), 200);
    }

    public function saveChanel(Request $request)
    {
        $user = $this->auth($request);
        $chanel = Chanel::addOrUpdate($user, $request);
        return response()->json(new ChanelResource($chanel), 200);
    }


    public function saveProgram(Request $request, Chanel $chanel)
    {
        if ($request->get('start') > 0) {
            $program = Program::addOrUpdate($chanel, $request);
            $chanel->exportXML();
            return response()->json(new ProgramResource($program), 200);
        }

        return response()->json($request->all(), 200);
    }


    public function deleteChanel(Request $request, Chanel $chanel)
    {
        $chanel->programs()->delete();
        $chanel->delete();
        return true;
    }

    public function deleteProgram(Request $request, Program $program)
    {
        $program->deleteProgram();
        return response()->json($program, 200);
    }



    public function exportChanel(Request $request, Chanel $chanel)
    {
        return ExportXML::exportXML($chanel);
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
