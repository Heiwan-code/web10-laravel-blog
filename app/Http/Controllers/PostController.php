<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Like;
use App\Models\Post;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    //

    function like(Request $request): RedirectResponse {

        // check if user has already liked this post
        $user = $request->user();
        $userId = $user->id;
        $postId = request('id');
        $post = Post::find($postId);

        $foundLike = Like::where('user_id', $userId)
            ->where('post_id', $postId)
            ->first();
        // if already liked, then remove like
        if($foundLike) {
            $foundLike->delete();
            $post->likes--;
            $post->save();
            return back()->withInput();
        }

        $post->likes++;
        $post->save();

        $like = new Like();
        $like->post()->associate($post);
        $like->user()->associate($user);

        $like->save();

        return back()->withInput();

    }

    function showAll(Request $request) {

        $allPosts = Post::all();
        $sorted = $allPosts->sortBy('created_at', SORT_REGULAR, true);

        return view('dashboard', ["data" =>  $sorted]);
    }

    function showFollowingPosts() {
        $user = Auth::user();
        // get all posts from users that auth user follows
        $followingIdList = $user->following->pluck('following_id');
       
        $posts = Post::whereIn('user_id', $followingIdList)->get();
        $sorted = $posts->sortBy('created_at', SORT_REGULAR, true);

        return view('dashboard', ["data" =>  $sorted]);
    }

    function create() {
        return view('post.create');
    }

    function store(Request $formData) {
        $formData->validate([
            'title' => ['required', 'string', 'max:50', 'min:4'],
            'body' => ['required', 'string', 'max:900', 'min:4']
        ]);

        $user = $formData->user();
        $excerpt = substr($formData->body, 100, 100);
        $excerpt = $excerpt.'...';

        $post = $user->posts()->create([
            'title' => $formData->title,
            'excerpt' => $excerpt,
            'body' => $formData->body,
            'reposts' => 0,
            'likes' => 0,
        ]);

        return redirect("/dashboard");
    }
}
