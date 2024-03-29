<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Library\ExportXML;

class Program extends Model
{
    protected $guarded = ['id'];

    public function chanel()
    {
        return $this->belongsTo(Chanel::class);
    }

    public static function addOrUpdate($chanel, $request)
    {
        $program = Program::firstOrCreate([
            'id' => $request->get('id'),
            'chanel_id' => $chanel->id
        ]);
        $program->update($request->all());

        $prevProgram = Program::where('start','<',$program->start)->where('chanel_id',$chanel->id)->orderBy('start','desc')->first();
        if ($prevProgram) {
            $prevProgram->stop = $program->start;
            $prevProgram->save();
        }

        $nextProgram = Program::where('start','>',$program->start)->where('chanel_id',$chanel->id)->orderBy('start','asc')->first();
        if ($nextProgram) {
            $program->stop = $nextProgram->start;
            $program->save();
        }

        ExportXML::exportAllXML($chanel->user);

        return $program;
    }

    public function deleteProgram()
    {

        $prevProgram = Program::where('start','<',$this->start)->where('chanel_id',$this->chanel_id)->orderBy('start','desc')->first();
        $nextProgram = Program::where('start','>',$this->start)->where('chanel_id',$this->chanel_id)->orderBy('start','asc')->first();

        if ($prevProgram && $nextProgram) {
            $prevProgram->stop = $nextProgram->start;
            $prevProgram->save();
        }

        $this->delete();
        return true;
    }
}