<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BookResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $user = $this->whenLoaded('user');

        return [
            'writer' => new UserResource($user),      
            'book_id' => $this->id,
            'title' => $this->title ,
            'description' => $this->description,
            'created_on' => $this->created_at,
            'updated_on' => $this->updated_at,
            'coments' => ComentResource::collection($this->whenLoaded("coment"))
        ];
    }
}
