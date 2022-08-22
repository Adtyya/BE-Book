<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\User;

class ComentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        // $user = User::find($this->user_id); 
        return [
            "user" => $this->whenLoaded("user")->name,
            "coment" => $this->coment,
            "coment_id" => $this->id,
        ];
    }
}
// (object) array('id' => $user->id, 'name' => $user->name )