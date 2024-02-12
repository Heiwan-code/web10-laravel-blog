<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\Follows;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

use function PHPSTORM_META\map;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    public function allPosts(Request $request)
    {
        $user = $request->user();
        $allUserPosts = Post::where('user_id','=',$user->id)->get();
        $sorted = $allUserPosts->sortBy('created_at', SORT_REGULAR, true);
        return view('profile.posts', 
        [
            "user" =>$user,
            "data" => $sorted
        ]);
    }

    public function userPosts(Request $request)
    {
        $userId = request('id');
        $user = User::find($userId);
        $allUserPosts = Post::where('user_id','=',$userId)->get();
        $sorted = $allUserPosts->sortBy('created_at', SORT_REGULAR, true);

        return view('profile.posts', 
        [
            "user" => $user,
            "data" => $sorted
        ]);
    }

    public function followers()
    {
        $userId = request('id');
        $user = User::find($userId);
        $followers = $user->followers;

        $followersUsers = $followers->map( function($follow) {
            return $follow->follower;
        });
        return view('profile.follows', [
            'user' => $user,
            'data' => $followersUsers 
        ]);
    }

    public function following() 
    {
        $userId = request('id');
        $user = User::find($userId);
        $following = $user->following;

        $followingUsers = $following->map( function($follow) {
            return $follow->following;
        });
        return view('profile.follows', [
            'user' => $user,
            'data' => $followingUsers
        ]);
    }
}
