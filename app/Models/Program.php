<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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

        $prevProgram = Program::where('start','<',$program->start)->orderBy('start','desc')->first();
        if ($prevProgram) {
            $prevProgram->stop = $program->start;
            $prevProgram->save();
        }

        $nextProgram = Program::where('start','>',$program->start)->orderBy('start','asc')->first();
        if ($nextProgram) {
            $program->stop = $nextProgram->start;
            $program->save();
        }

        return $program;
    }

    public function deleteProgram()
    {

        $prevProgram = Program::where('start','<',$this->start)->orderBy('start','desc')->first();
        $nextProgram = Program::where('start','>',$this->start)->orderBy('start','asc')->first();

        if ($prevProgram && $nextProgram) {
            $prevProgram->stop = $nextProgram->start;
            $prevProgram->save();
        }

        $this->delete();
        return true;
    }
}