<?php

namespace App\Http\Resources;

use App\Models\ArticleComment;
use Illuminate\Http\Resources\Json\Resource;

class CommentResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $comment = ArticleComment::find($this->id);

        return [
            "id"=> $this->id,
            "user"=> $this->user_id?[
                "name"=> $comment->user->name,
                "email"=> $comment->user->email
            ]:[
                "name"=> $this->name,
                "email"=> $this->email
            ],
            "comment"=> $this->comment
        ];
    }
}
