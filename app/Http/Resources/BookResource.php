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
        $user = $this->whenLoaded('users');

        return [
            'book_id' => $this->id,
            'title' => $this->title ,
            'description' => $this->description,
            'created_on' => $this->created_at,
            'updated_on' => $this->updated_at,
            'writer' => new UserResource($user)      
        ];
    }
}
