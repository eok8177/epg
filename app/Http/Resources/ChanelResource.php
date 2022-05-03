<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ChanelResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $start_date = $request->get('date', $this->lastDateFiled());

        return [
            "id" => $this->id,
            "chanel_id" => $this->chanel_id,
            "title" => $this->title,
            "date" => $start_date,
            "offset" => $this->offset,
            "cron" => (bool) $this->cron,
            'has_prev' => $this->hasPrev($start_date),
            "programs" => ProgramResource::collection($this->todayPrograms($start_date)),
        ];
    }
}
