<?php

namespace App\Http\Resources;

use App\Models\Article;
use Illuminate\Http\Resources\Json\Resource;

class ArticlesResource extends Resource
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
        return[
            'id'=>$this->id,
            'user'=>[
                'name'=> $article->user->name,
                'username'=> $article->user->username,
            ],
            'image'=>$this->image_id?env("STORAGE") !== "local"? env("STORAGE_PATH")."".$article->image->path:url("/storage/".$article->image->path):"https://via.placeholder.com/600x200.png?text=".$this->slug,
            'title'=>$this->title,
            'slug'=>$this->slug,
            'text'=>str_limit($this->text,100,"..."),
            'category'=> $article->category->name,
            'comments_count'=> count($article->comments),
            'created_at'=> $this->created_at->toFormattedDateString()
        ];
    }
}
