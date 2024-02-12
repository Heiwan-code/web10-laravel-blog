<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Like;
use App\Models\Post;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    //
    function store(Request $formData): RedirectResponse {
        $comment = new Comment();
        $comment->body = $formData->comment;
        $user = $formData->user();
        $postId = request('id');
        $post = Post::find($postId);
        $comment->post()->associate($post);
        $comment->user()->associate($user);
        $comment->likes = 0;
        $comment->save();

        return back()->withInput();
    }

    function like (Request $request): RedirectResponse {
        // check if user has already liked this post
        $user = $request->user();
        $userId = $user->id;
        $commentId = request('id');
        $comment = Comment::find($commentId);

        $foundLike = Like::where('user_id', $userId)
            ->where('comment_id', $commentId)
            ->first();
        // if already liked, then remove like
        if($foundLike) {
            $foundLike->delete();
            $comment->likes--;
            $comment->save();
            return back()->withInput();
        }

        $comment->likes++;
        $comment->save();

        $like = new Like();
        $like->comment()->associate($comment);
        $like->user()->associate($user);
        $like->save();

        return back()->withInput();
    }
}
