<?php

namespace App\Http\Resources\Party;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class PartyResource
 *
 * @package App\Http\Resources\Party
 */
class PartyResource extends JsonResource
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
        $registered = false;
        foreach ($this->users as $user) {
            if (isset(Auth()->user()->id) && $user->id == Auth()->user()->id) {
                $registered = true;
            }
        }
        $auth = Auth()->user() ? true : false;
        return [
            'id'            => $this->id,
            'name'          => $this->name,
            'date'          => $this->date,
            'duration'      => $this->duration,
            'capacity'      => $this->capacity,
            'description'   => $this->description,
            'cover_photo'   => $this->cover_photo,
            'start'         => $this->start,
            'registered'    => $registered,
            'auth'          => $auth,
            'filled'        => count($this->users) == $this->capacity ? true : false,
            'sing_up_link'  => route('parties.sing.up', ['party_id' => $this->id]),
            'sing_out_link' => route('parties.sing.out', ['party_id' => $this->id]),
        ];
    }
}
