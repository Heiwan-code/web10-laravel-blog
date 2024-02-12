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

    @foreach ($data as $user)
    <?php
        // dd($follow->user);
    ?>
        <div class="py-3">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <a href="/user/{{$user->id}}/posts">{{$user->name}}</a>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</x-app-layout>
