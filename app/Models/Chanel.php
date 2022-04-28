<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Chanel extends Model
{
    protected $guarded = ['id'];

    public function programs()
    {
        return $this->hasMany(Program::class);
    }

    public function todayPrograms($start_date)
    {
        return $this->programs
            ->where('start', '>=', $start_date)
            ->where('start', '<=', $start_date + 60*60*24);
    }

    public function hasPrev($start_date)
    {
        return $this->programs
            ->where('start', '<', $start_date)
            ->count() > 0;
    }

    public function lastDateFiled()
    {
        $start = strtotime('today midnight');
        $program = $this->programs()->orderBy('start','desc')->first();
        if ($program) {
            $start =  strtotime('today', $program->start);
        }
        return $start;
    }


    public static function addOrUpdate($user, $request)
    {
        $chanel = Chanel::firstOrCreate([
            'id' => $request->get('id'),
            'user_id' => $user->id
        ]);
        $chanel->update($request->all());

        return $chanel;
    }




}