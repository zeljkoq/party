<?php

namespace App\Http\Controllers\Admin\Api;

use App\Http\Requests\CreatePartyRequest;
use App\Http\Requests\UpdatePartyRequest;
use App\Http\Resources\Party\AdminPartyResource;
use App\Http\Resources\Tag\TagResource;
use App\Models\Party;
use App\Models\Tag;
use App\Http\Controllers\Controller;

/**
 * Class AdminPartyController
 *
 * @package App\Http\Controllers\Admin\Api
 */
class AdminPartyController extends Controller
{
    /**
     * @return array
     */
    public function index()
    {
        return [
            'parties' => AdminPartyResource::collection(Party::all()),
            'tags' => TagResource::collection(Tag::all())
        ];
    }

    /**
     * @param CreatePartyRequest $request
     *
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function store(CreatePartyRequest $request)
    {
        try {
            $party = new Party();
            $party->name = $request->name;
            $party->date = date('Y-m-d', strtotime($request->date));
            $party->duration = $request->duration;
            $party->capacity = $request->capacity;
            $party->description = $request->description;
            $party->cover_photo = $request->file('cover_photo')
                ->storeAs('cover_photos', rand(11111, 99999) . "_" . $request->cover_photo->getClientOriginalName());
            $party->user_id = Auth()->user()->id;
            $party->save();
            $tags = $this->createTagsIfNotExists($request->tags);
            foreach ($tags as $key => $tag) {
                if (!is_numeric($tag)) {
                    $newTag = new Tag;
                    $newTag->name = $tag;
                    $newTag->save();
                    $tags[$key] = $newTag->id;
                }
            }
            $party->tags()->attach($tags);
            return response([
                'data' => new AdminPartyResource($party),
                'success' => 'You have been successfully created party.'
            ]);
        } catch (\Exception $e) {
            return response([
                'error' => 'Error! Please, try again.'
            ]);
        }
    }

    /**
     * @param int $party_id
     *
     * @return array
     */
    public function show($party_id)
    {
        return [
            'parties' => new AdminPartyResource(Party::findOrFail($party_id)),
            'tags' => TagResource::collection(Tag::all())
        ];
    }

    /**
     * @param UpdatePartyRequest $request
     * @param int                $party_id
     *
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function update(UpdatePartyRequest $request, $party_id)
    {
        try {
            $party = Party::findOrFail($party_id);
            $party->name = $request->name;
            $party->date = date('Y-m-d', strtotime($request->date));
            $party->duration = $request->duration;
            $party->capacity = $request->capacity;
            $party->description = $request->description;
            $party->save();
            $tags = $this->createTagsIfNotExists($request->tags);
            $party->tags()->sync($tags);
            return response([
                'data' => new AdminPartyResource($party),
                'success' => 'You have been successfully updated party.'
            ]);
        } catch (\Exception $e) {
            return response([
                'error' => 'Error! Please, try again.'
            ]);
        }
    }

    /**
     * @param int $party_id
     *
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function delete($party_id)
    {
        try {
            $party = Party::findOrFail($party_id);
            $party->delete();
            return response([
                'id' => $party_id,
                'success' => 'You have been successfully deleted party.'
            ]);
        } catch (\Exception $e) {
            return response([
                'error' => 'Error! Please, try again.'
            ]);
        }
    }

    public function createTagsIfNotExists($tags)
    {
        foreach ($tags as $key => $tag) {
            if (!is_numeric($tag)) {
                $newTag = new Tag;
                $newTag->name = $tag;
                $newTag->save();
                $tags[$key] = $newTag->id;
            }
        }
        return $tags;
    }
}
