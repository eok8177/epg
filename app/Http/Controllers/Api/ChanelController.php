<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Chanel;
use App\Models\Program;

use App\Http\Resources\ChanelsResource;
use App\Http\Resources\ChanelResource;

class ChanelController extends Controller
{
    public function chanels(Request $request)
    {
        $chanels = Chanel::all();

        $data = ChanelsResource::collection($chanels)->response()->getData(true);

        return response()->json($data['data'], 200);
    }

    public function chanel(Request $request, Chanel $chanel)
    {
        $data = new ChanelResource($chanel);

        // $data['date'] = time();
// dd($data);
        return response()->json($data, 200);
    }

}
