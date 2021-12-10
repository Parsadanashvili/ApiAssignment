<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ArticleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id'=>$this['id'],
            'title'=>$this['title'],
            'created_at'=>$this['created_at'],
            'comment_count' => $this['comment_count'],
            'view_count' => $this['view_count'],
            'tags' => $this['tags_through'],
        ];
    }
}
