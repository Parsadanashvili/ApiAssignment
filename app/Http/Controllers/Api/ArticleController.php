<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ArticleResource;
use App\Http\Resources\CommentResource;
use App\Http\Resources\TagResource;
use App\Models\Article;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\DB;

class ArticleController extends Controller
{
    /**
     * Display a listing of the articles.
     *
     */
    public function index(Request $request)
    {
        $sort = $request->query('sort', 'created_at');
        $order = $request->query('order', 'desc');
        $limit = $request->query('limit', 10);
        $paginate = $request->query('paginate', '');
        $page = $request->query('page', 1);

        //Validate Request
        if(!in_array($sort, ['view_count', 'comment_count', 'created_at']) || !in_array($order, ['asc', 'desc']) || $paginate != null && (int)$paginate <= 0) return response()->json(['message' => 'Bad Request'], 400);

        //Query
        $query = Article::query();

        //Pagination Offset
        $offset = (($page - 1)  * (int)$paginate);

        //Filter Query
        $articles = $this->filterQuery($query, $sort, $order, $limit, $offset, $paginate);

        //Return Article Collection
        return ArticleResource::collection($articles);
    }

    /**
     * Filter Article
     *
     */
    private function filterQuery($query, $sort = 'created_at', $order = 'desc', $limit = 10, $offset = 0, $paginate = 0): array
    {
        $query->with('commentsThrough');
        $query->with('tagsThrough');

        $query->withCount(['commentsThrough as comment_count' => function($q){
            $q->select(DB::raw('count(*)'));
        }]);

        $query->orderBy($sort, $order);

        $query->take($limit);
        $articles = $query->get()->toArray();
        $result = $articles;
        if((int)$paginate > 0){
            $result = array_slice($result, $offset, $paginate);
        }
        return $result;
    }

    /**
     * Display a listing of the article comments.
     *
     */
    public function articleComments($id, Request $request){
        $sort = $request->query('sort', 'created_at');
        $order = $request->query('order', 'desc');

        //Validate Request
        if($sort != 'created_at' || !in_array($order, ['asc', 'desc'])) return response()->json(['message' => 'Bad Request'], 400);

        $article = Article::where('id', $id)->first();
        if(!$article) return response()->json(['message' => 'Article not found'], 404);

        $comments = $article->commentsThrough()->orderBy($sort, $order)->get();

        return CommentResource::collection($comments);
    }

    /**
     * Display a listing of the tags.
     *
     */
    public function tags(Request $request){
        $sort = $request->query('sort', 'article_count');
        $order = $request->query('order', 'desc');

        //Validate Request
        if(!in_array($sort, ['article_count', 'created_at']) || !in_array($order, ['asc', 'desc'])) return response()->json(['message' => 'Bad Request'], 400);

        //Query
        $query = Tag::query();

        //Filter Query
        $tags = $this->filterTagQuery($query, $sort, $order);

        //Return Tag Collection
        return TagResource::collection($tags);
    }

    /**
     * Filter Tags
     *
     */
    private function filterTagQuery($query, $sort, $order){
        $query->withCount(['articles as article_count' => function($q){
            $q->select(DB::raw('count(*)'));
        }]);
        $query->orderBy($sort, $order);
        return $query->get();
    }

    /**
     * Display a listing of the articles by tag.
     *
     */
    public function articlesByTags($id, Request $request){
        $sort = $request->query('sort', 'created_at');
        $order = $request->query('order', 'desc');
        $limit = $request->query('limit', 10);
        $paginate = $request->query('paginate', '');
        $page = $request->query('page', 1);

        //Validate Request
        if(!in_array($sort, ['view_count', 'comment_count', 'created_at']) || !in_array($order, ['asc', 'desc']) || $paginate != null && (int)$paginate <= 0) return response()->json(['message' => 'Bad Request'], 400);

        $tag = Tag::where('id', $id)->first();
        if(!$tag) return response()->json(['message' => 'Tag not found'], 404);

        $query = $tag->articles();

        //Pagination Offset
        $offset = (($page - 1)  * (int)$paginate);

        //Filter Query
        $articles = $this->filterQuery($query, $sort, $order, $limit, $offset, $paginate);

        //Return Article Collection
        return ArticleResource::collection($articles);
    }

    public function show(){
        abort(404);
    }
}
