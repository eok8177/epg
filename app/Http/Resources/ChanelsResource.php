<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class ChanelsResource extends ResourceCollection
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $offsets = [
            '0' => '+0',
            '-60' => '+1',
            '-120' => '+2',
            '-180' => '+3',
            '-240' => '+4',
        ];

        return [
            'data' => ChanelWithoutProgramsResource::collection($this->collection),
            'offsets' => (object) $offsets,
        ];
    }
}
