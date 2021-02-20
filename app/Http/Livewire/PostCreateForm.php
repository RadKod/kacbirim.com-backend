<?php

namespace App\Http\Livewire;

use App\Models\Countries;
use Livewire\Component;

class PostCreateForm extends Component
{
    public $title;
    public $countries;

    protected $rules = [
        'title' => 'required|min:6',
        'countries' => 'required|array|max:4|min:2',
    ];

    public function render()
    {
        $countries_data = Countries::query()->get();
        return view('livewire.post-create-form', compact('countries_data'));
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function savePost()
    {
        $validatedData = $this->validate();
        dd($validatedData);
        return redirect()->route('posts');
    }
}
