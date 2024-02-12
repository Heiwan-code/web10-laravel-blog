<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Follows;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FollowsController extends Controller
{
    //
    function follow() 
    {
        $currentUser = Auth::user();
        // check if user already follows
        $userToFollowId = request('id');
        $userToFollow = User::find($userToFollowId);
        $userFollowers = $userToFollow->followers;
        // check if user even has any followers
        if ($userFollowers) {
            $userAlreadyFollow = $userToFollow->alreadyFollowing();
            if( $userAlreadyFollow ) {
                // unfollow
                $userAlreadyFollow->delete();
                return back()->withInput();
            }
        }
        $follow = new Follows();
        $follow->follower()->associate($currentUser);
        $follow->following()->associate($userToFollow);
        $follow->save();
        return back()->withInput();
    }
}
