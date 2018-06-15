<?php

namespace App\Http\Resources\Song;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class SongResource
 *
 * @package App\Http\Resources\Song
 */
class SongResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request $request
     *
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id'       => $this->id,
            'name'     => $this->name,
            'author'   => $this->author,
            'link'     => $this->link,
            'duration' => $this->duration,
        ];
    }
}
