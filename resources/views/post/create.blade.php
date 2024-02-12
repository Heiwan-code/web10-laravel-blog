<x-app-layout>
    <h1>Create new post:</h1>

    <form action="/create-post" method="post">
        @csrf

        <input type="text" name="title" id="title" placeholder="title">

        <textarea name="body" id="body" placeholder="content"></textarea>
        
        <x-primary-button class="ms-3">
            Post!
        </x-primary-button>
    </form>

</x-app-layout>
