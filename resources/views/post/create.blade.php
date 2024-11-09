@extends('layouts.app')

@section('content')
<form method="POST" action="{{ route('posts.store') }}">
    @csrf
    <div class="form-group mb-2 flex">
        <label for="title" class="w-1/12 leading-10">
            Title
        </label>
        <input type="text" class="form-control w-full" name="title" placeholder="Title">
    </div>
    <div class="form-group mb-2 flex">
        <label for="content" class="w-1/12 leading-10">
            Content
        </label>
        <textarea class="form-control w-full" name="content" rows="8" placeholder="Content"></textarea>
    </div>
    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
        Submit
    </button>
</form>
@endsection
