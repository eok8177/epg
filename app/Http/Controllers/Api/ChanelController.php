<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

use App\Excel\SelectImport;

use App\Models\User;
use App\Models\Chanel;
use App\Models\Program;

use App\Http\Resources\ChanelsResource;
use App\Http\Resources\ChanelResource;
use App\Http\Resources\ProgramResource;

use App\Library\ExportXML;

class ChanelController extends Controller
{
    private $user;

    public function __construct(Request $request)
    {
        $token = $request->header('Authorization');
        $id = $request->header('Client');
        $client = User::where('id',$id)
            ->where('token',$token)
            ->first();

        if (!$client) {
            exit(); //404
        }
        $this->user = $client;
    }

    public function chanels()
    {
        $chanels = Chanel::where('user_id',$this->user->id)->get();
        return response()->json(new ChanelsResource($chanels), 200);
    }

    public function chanel(Chanel $chanel)
    {
        if ($chanel->user_id != $this->user->id) abort('404');
        return response()->json(new ChanelResource($chanel), 200);
    }

    public function saveChanel(Request $request)
    {
        $chanel = Chanel::addOrUpdate($this->user, $request);
        return response()->json(new ChanelResource($chanel), 200);
    }


    public function saveProgram(Request $request, Chanel $chanel)
    {
        if ($request->get('start') > 0) {
            $program = Program::addOrUpdate($chanel, $request);
            return response()->json(new ProgramResource($program), 200);
        }
        return response()->json($request->all(), 200);
    }


    public function deleteChanel(Chanel $chanel)
    {
        $chanel->programs()->delete();
        $chanel->delete();
        return true;
    }

    public function deleteProgram(Program $program)
    {
        $program->deleteProgram();
        return response()->json($program, 200);
    }



    public function exportChanel(Chanel $chanel)
    {
        return ExportXML::exportXML($chanel);
    }


    public function uploadFile(Request $request)
    {
        $request->validate([
            'file' => ['required','mimes:xlsx','max:10000'],
        ]);

        $filename = $this->user->id.'.xlsx';
        Storage::disk('public')->delete($filename);

        $uploadedFile = $request->file('file');

        Storage::disk('public')->putFileAs(
            'tmp/',
            $uploadedFile,
            $filename
        );

        $collection = (new SelectImport)->toCollection('tmp/'.$filename, 'public');
        $table = $collection[0]->take(15);

        $table = $table->map(function($item) {
            $item = $item->slice(0, 5);
            if(!is_string($item[0]) && $item[0] > 0) {
                $item[0] = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($item[0])->format('d.m.Y');
            }
            if(!is_string($item[1])) {
                $item[1] = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($item[1])->format('H:i');
            }
            return $item;
        });

        return response()->json([
            'table' => $table,
            'filename' => $filename,
            'fields' => $this->fields,
        ],200);
    }

    public function parseFile(Request $request)
    {
        $filename = $request->filename;
        $chanel_id = $request->chanel;
        $select = $request->get('select', []);

        $rows = [];
        $collection = (new SelectImport)->toCollection('tmp/'.$filename, 'public');
        foreach ($collection[0] as $r_id => $row) {
            $data = [];
            foreach ($row as $col_id => $col) {
                if (array_key_exists($col_id, $select) && array_key_exists($select[$col_id], $this->fields)) {
                    $data[$select[$col_id]] = $col;
                }
            }
            if(array_key_exists('date', $data) && array_key_exists('time', $data) && array_key_exists('title', $data)) {
                if(!is_string($data['date']) && $data['date'] > 0) {
                    $data['date'] = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($data['date'])->format('d.m.Y');
                }
                if(!is_string($data['time'])) {
                    $data['time'] = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($data['time'])->format('H:i');
                }
                $rows[] = [
                    'start' => strtotime($data['date'].' '.$data['time']),
                    'title' => $data['title'],
                    'description' => array_key_exists('description', $data) ? $data['description'] : NULL,
                ];
            }
        }

        $skip_count = 0;
        $added_count = 0;
        $skipped_rows = [];
        $chanel = Chanel::findOrFail($chanel_id);
        $last_programm = Program::where('chanel_id',$chanel->id)->orderBy('start', 'desc')->first();
        $first_step = $last_programm ? true : false;
        $prev_start = $last_programm ? $last_programm->start : 0;

        foreach($rows as $index => $item) {
            if($item['start'] > 0 && !empty($item['title'])) {
                // $item['start'] = $item['start'] - $chanel->offset*60; // TODO minus offset if need
                if($first_step) {
                    if($last_programm->start >= $item['start']) { // skip item
                        $skip_count++;
                        $skipped_rows[] = $item;
                        continue;
                    }
                    // update last record in DB
                    $last_programm->stop = $item['start'];
                    $last_programm->save();
                    $first_step = false;
                }
                
                if(array_key_exists($index+1, $rows)) {
                    $stop = $rows[$index+1]['start']; //  $rows[$index+1]['start'] - $chanel->offset*60; // TODO minus offset if need
                    $item['stop'] = $stop > $item['start'] ? $stop : NULL;
                }

                if($item['start'] <= $prev_start) { // skip exists time
                    $skip_count++;
                    $skipped_rows[] = $item;
                    continue;
                }

                $item['chanel_id'] = $chanel->id;
                Program::create($item);

                $prev_start = $item['start'];
                $added_count++;
            } else {
                $skip_count++;
                $skipped_rows[] = $item;
            }

        }

        if($added_count > 0) ExportXML::exportXML($chanel);

        return response()->json([
            'status' => 'success',
            'skip_count' => $skip_count,
            'skipped_rows' => $skipped_rows,
            'added_count' => $added_count,
            'chanel' => $chanel,
            // 'rows' => $rows,
        ],200);
    }

    private $fields = [
        'date' => 'Date',
        'time' => 'Time',
        'title' => 'Title',
        'description' => 'Description',
    ];

}
