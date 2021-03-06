<?php

namespace App\Http\Resources\Party;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class AdminPartyResource
 *
 * @package App\Http\Resources\Party
 */
class AdminPartyResource extends JsonResource
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
            'id'           => $this->id,
            'name'         => $this->name,
            'date'         => $this->date,
            'duration'     => $this->duration,
            'capacity'     => $this->capacity,
            'description'  => $this->description,
            'cover_photo'  => $this->cover_photo,
            'tags'         => $this->tags,
            'start'        => $this->start,
            'start_link'   => route('admin.parties.start', ['party_id' => $this->id]),
            'details_link' => route('admin.parties.details', ['party_id' => $this->id]),
            'delete_link'  => route('admin.parties.delete', ['party_id' => $this->id]),
        ];
    }
}
