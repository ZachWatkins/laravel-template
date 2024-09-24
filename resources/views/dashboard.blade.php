<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <h1 class="text-2xl font-semibold inline mb-6">{{ __("Posts") }}</h1>
            <div class="bg-white dark:bg-gray-800 overflow-hidden p-6 mt-6 shadow-sm sm:rounded-lg">
                <div class="text-gray-900 dark:text-gray-100">
                    <a href="{{ route('posts.index') }}" class="underline text-blue-500 hover:text-blue-700">{{ __("View Posts") }}</a><br />
                    <a href="{{ route('posts.create') }}" class="underline text-blue-500 hover:text-blue-700">{{ __("Create Post") }}</a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
