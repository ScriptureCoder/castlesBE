<?php
namespace App\Http\Controllers\Developer;
use App\Http\Resources\ArticleResource;
use App\Http\Resources\CommentResource;
use App\Models\Article;
use App\Models\ArticleComment;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
class ArticleController extends Controller
{
    public function getAll()
    {
        $response['status'] = 1;
        $response['data']= ArticleResource::collection(Article::all());
        return response()->json($response, 200);
    }
    public function show($id)
    {
        $response['status'] = 1;
        $response['data']= new ArticleResource(Article::find($id));
        return response()->json($response, 200);
    }
    public function getArticleComments($article)
    {
        $response['status'] = 1;
        $response['data']= CommentResource::collection(Article::find($article)->comments);
        return response()->json($response, 200);
    }
    public function getDeveloperArticle()
    {
        $response['status'] = 1;
        $response['data']= ArticleResource::collection(Auth::user()->developer->articles);
        return response()->json($response, 200);
    }
    public function post(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'body' => 'required',
        ]);
        $article= new Article();
        $article->developer_id= Auth::user()->developer->id;
        $article->title= $request->title;
        $article->content= $request->body;
        $article->status= 0;
        $article->save();
        $response['status'] = 1;
        $response['message']= "Posted Successfully!, would be reviewed by the admin";
        return response()->json($response, 200);
    }
    public function editArticle(Request $request, $id)
    {
        $request->validate([
            'title' => 'required',
            'body' => 'required',
        ]);
        $article= Article::find($id);
        $article->title= $request->title;
        $article->content= $request->body;
        $article->save();
        $response['status'] = 1;
        $response['message']= "Edited Successfully!";
        return response()->json($response, 200);
    }
    public function comment(Request $request)
    {
        $request->validate([
            'comment' => 'required',
        ]);
        $comment= new ArticleComment();
        $comment->devloper_id= Auth::user()->developer->id;
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
