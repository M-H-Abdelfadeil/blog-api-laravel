<?php

namespace App\Http\Resources\PublicResource;

use Illuminate\Http\Resources\Json\JsonResource;

class PostsUserResource extends JsonResource
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
            'id'        =>$this->id,
            'title'     =>$this->title,
            'content'   =>$this->content,
            'date'      =>$this->created_at->format('d-m-Y h:i a')
        ];
    }
}
