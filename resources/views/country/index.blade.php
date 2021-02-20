<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Countries') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl px-4 py-3">
                <table class="table-auto w-full">
                    <thead>
                    <tr>
                        <th class="w-1/2">Name</th>
                        <th class="w-1/4">Code</th>
                        <th class="w-1/4">Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($countries as $country)
                        <tr>
                            <td class="border border-emerald-500 px-2 py-2">{{ $country->name }}</td>
                            <td class="border border-emerald-500 px-2 py-2">{{ $country->code }}</td>
                            <td class="border border-emerald-500 px-2 py-2">butnlar</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            <div class="px-4 py-3 bg-gray-50 text-right sm:px-6 shadow sm:rounded-bl-md sm:rounded-br-md">
                {{ $countries->links() }}
            </div>

        </div>
    </div>
</x-app-layout>
