<x-app-layout>
    <x-slot name="header">
        <div class="grid grid-cols-6 gap-4">
            <div class="col-start-1 col-end-3">
                <h2 class="font-semibold w-8 text-xl text-gray-800 leading-tight">
                    {{ __('Posts') }}
                </h2>
            </div>
            <div class="col-end-8 col-span-2">
                <a href="{{ route('posts.create') }}"
                        class="flex float-right items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:shadow-outline-gray disabled:opacity-25 transition ease-in-out duration-150"
                >
                    New Post
                </a>
            </div>
        </div>

    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl px-4 py-3">
                <table class="table-fixed w-full">
                    <thead>
                    <tr>
                        <th class="w-1/3">Title</th>
                        <th class="w-1/4">Countries - wage</th>
                        <th class="w-1/5">Tags</th>
                        <th class="w-1/8">Unit</th>
                        <th class="w-1/6">Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($posts as $post)
                        <tr>
                            <td class="border border-emerald-500 px-2 py-2">{{ $post->title }}</td>
                            <td class="border border-emerald-500 px-2 py-2">
                                <ul>
                                    @foreach($post->countries as $country)
                                        <li>
                                            {{ $country->country->name }}: {{ $country->minimum_wage }}
                                            {{ $country->country->currency }}
                                        </li>
                                    @endforeach
                                </ul>
                            </td>
                            <td class="border border-emerald-500 px-2 py-2">
                                <ul>
                                    @foreach($post->tags as $tag)
                                        <li>
                                            {{ $tag->tag->name }}
                                        </li>
                                    @endforeach
                                </ul>
                            </td>
                            <td class="border border-emerald-500 px-2 py-2">{{ $post->unit }}</td>
                            <td class="border border-emerald-500 px-2 py-2">butnlar</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            <div class="px-4 py-3 bg-gray-50 text-right sm:px-6 shadow sm:rounded-bl-md sm:rounded-br-md">
                {{ $posts->links() }}
            </div>

        </div>
    </div>
</x-app-layout>
