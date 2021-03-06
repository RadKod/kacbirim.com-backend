@extends('layouts.app')

@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl px-4 py-3">
                @livewire('post-create-form')
            </div>
        </div>
    </div>
@endsection
