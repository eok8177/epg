<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Library\ExportXML;

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
            ->where('start', '<', $start_date + 60*60*24);
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


    public static function nextDayCommand()
    {
        $one_day = 60*60*24;

        $chanels = Chanel::where('cron', 1)
            ->where(function ($q) {
                $q->orWhere('cron_run_at','<', date('Y-m-d H:i:s',strtotime('-23 hours')) );
                $q->orWhereNull('cron_run_at');
            })
            ->get();

        foreach ($chanels as $chanel) {

            $programs = self::searchLastPrograms($chanel);

            $last_index = $programs->count() - 1;

            // Duplicate records
            foreach ($programs as $index => $program) {
                $newProgram = $program->replicate();
                $newProgram->start = $newProgram->start + $one_day;
                $newProgram->stop = $newProgram->stop ? $newProgram->stop + $one_day : NULL;
                $newProgram->save();
                if ($index == 0) {
                    $first_start = $newProgram->start;
                }
                if ($index == $last_index) {
                    $program->stop = $first_start;
                    $program->save();
                }
            }

            $chanel->cron_run_at = date('Y-m-d H:i:s');
            $chanel->save();
            ExportXML::exportXML($chanel);
        }

        return true;
    }

    private static function searchLastPrograms($chanel)
    {
        // TODO get only last day
        // maybe compare last record start time ?
        $start_time = strtotime('today midnight');
        $one_day = 60*60*24;

        for ($i=0; $i <20 ; $i++) {

            $programs = $chanel->programs()
                ->where('start','>=',$start_time)
                ->orderBy('start','asc')
                ->get();
            if ($programs->count() > 0) return $programs;
            $start_time = $start_time - $one_day;
        }
        return $programs;
    }


}