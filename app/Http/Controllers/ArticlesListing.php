<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ArticlesListing extends Controller
{
    public function get_the_articles($id = null)
    {
        $a = DB::table('articles');

        if ($id == null) {
            $results = $a->get();
        } else {
            $results = $a->get();
        }

        if ($id == null) {
            return $results;
        } else {
            if (count($results) == 1) {
                $single = $results[0];
                return $single;
            }
            else {
                return $results;
            }
        }

    }

    public function article_edits(Request $request, $id) {
        return Article::query()->where('id', $id)->get()->first()->update($request->all());
    }

    public function get_author_articles($author_id)
    {
        $articles = Article::get();
        $results = collect();

        foreach ($articles as $a) {
            if ($a->authors->id == $author_id) {
                $results->push($a);
            }
        }

        return $results;
    }


    public function delete_article($article_id)
    {
        $to_Delete = Article::find($article_id);
        $user = Auth::id();

        if ($to_Delete->authors->id == $user) {
            Article::destroy($article_id);
        } else {

        }

        return response()->json(["deleted!"]);
    }

    public function make_new_article(Request $request)
    {
        if (strlen($request->title) > 50) {
            return response()->json(['error' => "title is too long!"]);
        }

        if (strlen($request->body) < 30) {
            return response()->json(['error' => "body is too short!"]);
        }

        Article::create(array_merge($request->all(), ['user_id' => $request->user_id ?? Auth::id()]));

        return response()->json(['success' => "everything was just right!"]);
    }

}
