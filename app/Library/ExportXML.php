<?php

namespace App\Library;

use PhpOffice\PhpSpreadsheet\Shared\XMLWriter;
use Illuminate\Support\Facades\Storage;

class ExportXML
{

    public static function exportXML($chanel)
    {
        $from_date = strtotime('-15 days');

        $programs = $chanel->programs()
            ->where('stop','>',0)
            ->where('start','>=',$from_date)
            ->orderBy('start','asc')
            ->get();

        $chanels = [
            [
                'chanel_id' => $chanel->chanel_id,
                'title' => $chanel->title,
                'offset' => $chanel->offset,
                'programs' => $programs
            ],
        ];

        $filename = $chanel->id.'_'.$chanel->chanel_id.'.xml';

        self::generateXML($chanels, $filename);

        return 'epg/'.$filename;
    }


    public static function exportAllXML($user)
    {
        $from_date = strtotime('-15 days');

        $chanels = [];

        foreach ($user->chanels as $chanel) {
            $programs = $chanel->programs()
                ->where('stop','>',0)
                ->where('start','>=',$from_date)
                ->orderBy('start','asc')
                ->get();

            $chanels[] = [
                'chanel_id' => $chanel->chanel_id,
                'title' => $chanel->title,
                'offset' => $chanel->offset,
                'programs' => $programs
            ];
        }

        $filename = $user->id.'_all.xml';

        self::generateXML($chanels, $filename);

        return 'epg/'.$filename;
    }


    private static function generateXML($chanels, $filename)
    {
        $xmlWriter = new XMLWriter();
        $xmlWriter->openMemory();
        $xmlWriter->startDocument('1.0', 'UTF-8');
        $xmlWriter->startDtd('tv', 'SYSTEM', 'xmltv.dtd');
        $xmlWriter->endDtd();

        Storage::disk('public')->put('epg/'.$filename, $xmlWriter->flush(true));

        $xmlWriter->startElement('tv');
        $xmlWriter->writeAttribute('generator-info-name', 'epg-gen');

        foreach ($chanels as $chanel) {
            $xmlWriter->startElement('channel');
            $xmlWriter->writeAttribute('id', $chanel['chanel_id']);
              $xmlWriter->startElement('display-name');
              $xmlWriter->writeAttribute('lang', 'en');
                $xmlWriter->text($chanel['title']);
              $xmlWriter->endElement();
            $xmlWriter->endElement(); // channel
        }

        $i = 0;
        foreach ($chanels as $chanel) {
            foreach ($chanel['programs'] as $program) {

              $start = gmdate('YmdHis', $program->start + $chanel['offset']*60 );
              $stop = gmdate('YmdHis', $program->stop + $chanel['offset']*60 );

              $xmlWriter->startElement('programme');
              $xmlWriter->writeAttribute('channel', $chanel['chanel_id']);
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
                Storage::disk('public')->append('epg/'.$filename,  $xmlWriter->flush(true));
              }
              $i++;
            }
        }


        $xmlWriter->endElement(); // tv

        // Final flush to make sure we haven't missed anything
        Storage::disk('public')->append('epg/'.$filename,  $xmlWriter->flush(true));

        return;
    }
}