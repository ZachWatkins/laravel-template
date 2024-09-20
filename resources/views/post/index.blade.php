@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div>
                <div class="py-2 flex items-center">
                    <h1 class="text-2xl font-semibold inline">Posts</h1> <span class="text-4xl leading-4 font-semibold mx-4">&rarr;</span> <a href="{{ route('posts.create') }}" class="bg-green-500 hover:bg-green-700 text-white font-bold py-1 px-3 rounded">
                        Create
                    </a>
                </div>
                <div class="card-body mx-2">
                    <table class="table-auto w-full text-left">
                        <thead>
                            <tr>
                                <th class="px-2 py-2">Title</th>
                                <th class="px-2 py-2 w-full">Content</th>
                                <th class="px-2 py-2">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($posts as $post)
                            <tr>
                                <td class="whitespace-nowrap px-2 py-2">{{ $post->title }}</td>
                                <td class="px-2 py-2">{{ $post->content }}</td>
                                <td class="whitespace-nowrap px-2 py-2">
                                    <a href="{{ route('posts.edit', $post->id) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Edit</a>
                                    <form method="POST" action="/posts/{{ $post->id }}" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                                            Delete
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    @endsection
