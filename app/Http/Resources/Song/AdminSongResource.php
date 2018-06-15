<?php

namespace App\Http\Resources\Song;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class AdminSongResource
 *
 * @package App\Http\Resources\Song
 */
class AdminSongResource extends JsonResource
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
            'id'          => $this->id,
            'name'        => $this->name,
            'author'      => $this->author,
            'link'        => $this->link,
            'duration'    => $this->duration,
            'delete_link' => route('admin.songs.delete', ['song_id' => $this->id]),
        ];
    }
}
