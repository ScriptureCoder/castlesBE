<?php

namespace App\Http\Resources;

use App\Models\Article;
use Illuminate\Http\Resources\Json\Resource;

class ArticleResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $article = Article::find($this->id);
        $next = Article::where("id", "<", $this->id)->first();
        $previous = Article::where("id", ">", $this->id)->first();
        return[
            'id'=>$this->id,
            'user'=>[
                'name'=> $article->user->name,
                'username'=> $article->user->username,
            ],
            'image'=>$this->image?url("/articles/".$this->image):"https://via.placeholder.com/600x200.png?text=".str_replace(" ", "+",$this->title),
            'title'=>$this->title,
            'short'=>str_limit($this->content,100),
            'text'=> $this->text,
            'category'=> $article->category->name,
            'created_at'=> $this->created_at->toFormattedDateString(),
            'comments'=> CommentResource::collection($article->comments),
            'previous'=> !$previous?:[
                "slug"=>$previous->slug,
                "title"=> $previous->title
            ],
            "next"=>!$next?:[
                "slug"=>$next->slug,
                "title"=> $next->title
            ],
        ];
    }
}
