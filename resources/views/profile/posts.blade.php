<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            User: {{$user->name}}
            @if ($user->id !== Auth::user()->id)
                <x-primary-button class="ms-3">
                    <a href="/follow/{{$user->id}}">
                        @if ($user->alreadyFollowing())
                        Unfollow
                        @else
                        Follow
                        @endif
                        </a>
                </x-primary-button>
            @endif
        </h2>

        <p>Followers: {{$user->followers->count()}} 
            <x-primary-button class="ms-3">
                <a href="/user/{{$user->id}}/followers/">
                    See All
                </a>
            </x-primary-button>
        </p>
        <p>Following: {{$user->following->count()}}
            <x-primary-button class="ms-3">
                <a href="/user/{{$user->id}}/following/">
                    See All
                </a>
            </x-primary-button>
        </p>
    </x-slot>

    @foreach ($data as $post)    
        <div class="py-3" id="post-{{$post->id}}">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <?php
                            $user = auth()->user();
                            $author = $post->user;
                            $userId = $user->id;
                            $postLikes = $post->likesTable;
                            $postIsLiked = $postLikes->where('user_id', $userId)->first();
                            $authorName = $author->name;
                        ?>
                        <a href="/user/{{$post->user->id}}/posts">post by:{{$authorName}}</a>
                        <p>{{$post->created_at}}</p>
                        <p>Likes: {{$post->likes}}</p>
                        <p>Reposts: {{$post->reposts}}</p>
                        <h1>{{$post->title}}</h1>
                        <p>{{$post->body}}</p>
                        <form action="/like-post/{{$post->id}}" method="post">
                            @csrf
                            <x-primary-button>
                                @if ($postIsLiked)
                                    Dislike
                                @else
                                    Like
                                @endif
                            </x-primary-button>
                        </form>
                        <form action="/comment-post/{{$post->id}}" method="post">
                            @csrf
                            <texTarea 
                            id="comment" 
                            name="comment"
                            placeholder="Leave your comment here..."
                            ></texTarea>
                            <x-primary-button>
                                Comment
                            </x-primary-button>
                        </form>
                        <div class="all-comments shadow-sm sm:rounded-lg">
                            <?php 
                                $postComments = $post->comments
                            ?>

                            @foreach ($postComments as $comment)
                                <div class="bg-gray-400 shadow-sm sm:rounded-lg text-white py-2">
                                    <a href="/user/{{$comment->user->id}}/posts">
                                        {{$comment->user->name}}
                                    </a>
                                    <p>{{$comment->body}}</p>
                                    <?php
                                    $commentLikes = $comment->likesTable;
                                    $commentIsLiked = 
                                    $commentLikes->where('user_id', $userId)->first();
                                    ?>
                                    <form action="/like-comment/{{$comment->id}}" method="post">
                                        @csrf
                                        <x-primary-button>
                                            {{$comment->likes}}
                                            @if ($commentIsLiked)
                                                Dislike
                                            @else
                                                Like
                                            @endif
                                        </x-primary-button>
                                    </form>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</x-app-layout>
