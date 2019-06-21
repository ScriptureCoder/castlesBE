<?php
namespace App\Http\Controllers\Developer;
use App\Http\Resources\ArticleResource;
use App\Http\Resources\ArticlesResource;
use App\Http\Resources\CommentResource;
use App\Models\Article;
use App\Models\ArticleComment;
use App\Models\PropertyAdvice;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
class ArticleController extends Controller
{
    public function categories()
    {
        $data = PropertyAdvice::all();

        return response()->json([
            "status"=> 1,
            "data"=> $data,
        ],200);
    }

    public function adviceArticles($slug)
    {
        $data = PropertyAdvice::where('slug', $slug)->first()->articles()->paginate;
        ArticlesResource::collection($data);

        return response()->json([
            "status"=> 1,
            "message"=> $data,
        ],200);
    }

    public function articles(Request $request)
    {
        $data = Article::paginate($request->paginate?$request->paginate:10);
        ArticlesResource::collection($data);

        return response()->json([
            "status"=> 1,
            "message"=> $data,
        ],200);
    }

    public function view($slug)
    {
        $data = Article::where('slug', $slug)->first();

        return response()->json([
            "status"=> 1,
            "data"=> new ArticleResource($data),
        ],200);
    }

    public function save(Request $request)
    {
        $slug = preg_replace("/[^a-zA-Z]/","-",strtolower($request->title));
        $check = Article::where('slug', $slug)->first();

        $request->validate([
            'title' => 'required',
            'text' => 'required',
        ]);

        $article= $request->id? Article::findOrFail($request->id):new Article();
        $article->user_id= Auth::id();
        $article->title= $request->title;
        if ($check && $check->id !== $request->id){
            $article->slug = $slug."-".rand(1000, 50000);
        }else{
            $article->slug = $slug;
        }
        $article->text= $request->text;
        $article->status= 0;
        $article->save();

        $response['status'] = 1;
        $response['message']= "Posted Successfully!,";
        return response()->json($response, 200);
    }


    public function comment(Request $request)
    {
        $request->validate([
            'comment' => 'required',
        ]);
        $comment= new ArticleComment();
        $comment->article_id= $request->article_id;

        if ($request->auth){
            $comment->user_id = $request->auth["id"];
        }else{
            $request->validate([
                'name' => 'required|string',
                'email' => 'required|string|email',
            ]);
            $comment->name = $request->name;
            $comment->email = $request->email;
        }

        $comment->comment= $request->comment;
        $comment->save();
        $response['status'] = 1;
        $response['message']= "Success!";
        return response()->json($response, 200);
    }

    public function editComment(Request $request, $id)
    {
        $request->validate([
            'comment' => 'required',
        ]);
        $comment= ArticleComment::find($id);
        $comment->comment= $request->comment;
        $comment->save();
        $response['status'] = 1;
        $response['message']= "Edited Successfully!";
        return response()->json($response, 200);
    }

    public function deleteArticle()
    {
        $article= Article::find(request('id'));
        $article->delete();
        $response['status'] = 1;
        $response['message']= "Success!";
        return response()->json($response, 200);
    }

    public function deleteComment()
    {
        $comment= ArticleComment::find(request('id'));
        $comment->delete();
        $response['status'] = 1;
        $response['message']= "Success!";
        return response()->json($response, 200);
    }
}
