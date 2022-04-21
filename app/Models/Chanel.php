<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use PhpOffice\PhpSpreadsheet\Shared\XMLWriter;

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




    public function exportXML($cron = false)
    {
        $from_date = strtotime('-15 days');

        $programs = $this->programs()
            ->where('stop','>',0)
            ->where('start','>=',$from_date)
            ->orderBy('start','asc')
            ->get();

        file_put_contents('epg/'.$this->id.'_'.$this->chanel_id.'.xml', '');

        $xmlWriter = new XMLWriter();
        $xmlWriter->openMemory();
        $xmlWriter->startDocument('1.0', 'UTF-8');
        $xmlWriter->startDtd('tv', 'SYSTEM', 'xmltv.dtd');
        $xmlWriter->endDtd();

        $xmlWriter->startElement('tv');
        $xmlWriter->writeAttribute('generator-info-name', 'epg-gen');

        $xmlWriter->startElement('channel');
        $xmlWriter->writeAttribute('id', $this->chanel_id);
          $xmlWriter->startElement('display-name');
          $xmlWriter->writeAttribute('lang', 'en');
            $xmlWriter->text($this->title);
          $xmlWriter->endElement();
        $xmlWriter->endElement();

        $i = 0;
        foreach ($programs as $program) {

          $start = gmdate('YmdHis', $program->start + $this->offset*60 );
          $stop = gmdate('YmdHis', $program->stop + $this->offset*60 );

          $xmlWriter->startElement('programme');
          $xmlWriter->writeAttribute('channel', $this->chanel_id);
          $xmlWriter->writeAttribute('start', $start.' +0000');
          $xmlWriter->writeAttribute('stop',  $stop.' +0000');


            $xmlWriter->startElement('title');
              $xmlWriter->text($program->title);
            $xmlWriter->endElement(); //title

            $xmlWriter->startElement('desc');
              $xmlWriter->text($program->description);
            $xmlWriter->endElement(); //desc


          $xmlWriter->endElement(); //programme

          // Flush XML in memory to file every 1000 iterations
          if (0 == $i%1000) {
              file_put_contents('epg/'.$this->id.'_'.$this->chanel_id.'.xml', $xmlWriter->flush(true), FILE_APPEND);
          }
          $i++;
        }

        $xmlWriter->endElement(); // tv

        // Final flush to make sure we haven't missed anything
        file_put_contents('epg/'.$this->id.'_'.$this->chanel_id.'.xml', $xmlWriter->flush(true), FILE_APPEND);

        return 'epg/'.$this->id.'_'.$this->chanel_id.'.xml';
        // if ($cron) return true;

        // if ($request->isMethod('post')) {
        //     return '<a href="epg/'.$this->id.'_'.$this->chanel_id.'.xml" target="_blank">Download</a>';
        // } else {
        //    $headers = [
        //      'Content-Type' => 'application/xml',
        //   ];

        //    return response()->download('epg/'.$this->id.'_'.$this->chanel_id.'.xml', $this->chanel_id.'.xml', $headers);
        // }
    }
}