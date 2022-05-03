<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ChanelWithoutProgramsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            "id" => $this->id,
            "chanel_id" => $this->chanel_id,
            "title" => $this->title,
            "offset" => $this->offset,
            "cron" => (bool) $this->cron,
            "has_programs" => $this->programs()->count() > 0
        ];
    }
}
