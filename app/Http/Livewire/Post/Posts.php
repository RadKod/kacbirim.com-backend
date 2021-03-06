<?php

namespace App\Http\Livewire\Post;

use App\Models\Countries;
use App\Models\PostCountry;
use App\Models\PostTag;
use App\Models\Tags;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use App\Models\Post as PostModel;

class Posts extends Component
{
    use WithPagination;
    use WithFileUploads;

    protected $paginationTheme = 'bootstrap';

    public $post_image = null;
    public $tags = [];
    public $country_wage = [];
    public $country_ids = [];
    public $country_names = [];
    public $title, $description, $image, $post_id, $confirming, $comparison_date, $unit;
    public $updateMode = false;

    public function render()
    {
        $countries_data = Countries::query()->get();
        $posts = PostModel::query()
            ->orderBy('id', 'desc')->paginate(10);
        return view('livewire.post.posts', [
            'countries_data' => $countries_data,
            'posts' => $posts
        ]);
    }

    public function mount()
    {

    }

    private function resetInputFields()
    {
        $this->title = null;
        $this->post_image = null;
        $this->description = null;
        $this->image = null;
        $this->post_id = null;
        $this->comparison_date = null;
        $this->unit = null;
        $this->confirming = null;
        $this->country_wage = [];
        $this->country_ids = [];
        $this->country_names = [];
        $this->tags = [];
        $this->emit('reset');
    }

    public function cancel()
    {
        $this->updateMode = false;
        $this->resetInputFields();
    }

    public function confirmDelete($id)
    {
        $this->confirming = $id;
    }

    public function openModal()
    {
        $this->post_id = null;
        $this->resetInputFields();
        // Clean errors if were visible before
        $this->resetErrorBag();
        $this->resetValidation();
    }

    public function closeModal()
    {
        $this->updateMode = false;
        $this->emit('closeModal');
        $this->resetInputFields();
    }

    public function create_or_update()
    {
        if ($this->post_id) {
            $this->update();
        } else {
            $this->store();
        }
    }

    public function add_tag($val)
    {
        $this->tags[] = $val;
    }

    public function remove_tag($val)
    {
        unset($this->tags[array_search($val, $this->tags)]);
    }

    public function add_city_id($val)
    {
        $this->country_ids[] = $val;
    }

    public function add_city_name($val)
    {
        $this->country_names[] = $val;
    }

    public function remove_city_id($val)
    {
        unset($this->country_ids[array_search($val, $this->country_ids)]);
    }

    public function remove_city_name($val)
    {
        unset($this->country_names[array_search($val, $this->country_names)]);
    }

    public function edit($id)
    {
        $this->resetInputFields();
        $this->updateMode = true;
        $post = PostModel::query()->with(['countries', 'countries.country', 'tags', 'tags.tag'])
            ->where('id', $id)->first();

        if (isset($post)) {
            $this->post_id = $id;
            $this->title = $post->title;
            $this->description = $post->description;
            $this->unit = $post->unit;
            $this->post_image = $post->image;
            $this->comparison_date = $post->comparison_date;

            $tags = [];
            $countries = [];
            foreach ($post->countries as $country) {
                $this->country_wage[$country->country->id] = $country->minimum_wage;
                $countries[] = [
                    'id'=>$country->country->id,
                    'value'=>$country->country->name,
                    'code'=>$country->country->code,
                    'currency'=>$country->country->currency,
                ];
            }
            foreach ($post->tags as $tag) {
                // $this->tags[] = $tag->tag->name;
                $tags[] = $tag->tag->name;
            }

            $this->emit('postEdit', [
                'tags' => $tags,
                'countries' => $countries
            ]);
        }

    }

    public function store()
    {
        $this->validate([
            'title' => 'required|min:6|unique:posts,title',
            'unit' => 'required|int',
            'comparison_date' => 'required|date',
            'image' => 'required|dimensions:min_width=100,min_height=200|mimes:jpeg,png,jpg,gif,svg',
            'country_ids' => 'required|array|min:2',
            'tags' => 'required|array|min:1',
            'description' => 'required|min:10',
        ]);
        $image_path = $this->image->store('posts', 'public');
        $post = PostModel::query()->create([
            'title' => $this->title,
            'slug' => Str::slug($this->title),
            'description' => $this->description,
            'image' => $image_path,
            'comparison_date' => $this->comparison_date,
            'unit' => $this->unit
        ]);
        foreach ($this->tags as $tag) {
            $tag = Tags::query()->firstOrCreate(
                ['name' => $tag, 'slug' => Str::slug($tag)]
            );
            PostTag::query()->create([
                'post_id' => $post->id,
                'tag_id' => $tag->id,
            ]);
        }
        foreach ($this->country_ids as $country_id) {
            $m_wage = $this->country_wage[$country_id] ?? null;
            PostCountry::query()->create([
                'post_id' => $post->id,
                'country_id' => $country_id,
                'minimum_wage' => $m_wage,
            ]);
        }
        session()->flash('message', 'Post Created Successfully.');
        $this->resetInputFields();
        $this->closeModal();
    }

    public function update()
    {
        $this->validate([
            'title' => 'required|min:6|unique:posts,title,' . $this->post_id,
            'unit' => 'required|int',
            'comparison_date' => 'required|date',
            'image' => 'nullable|dimensions:min_width=100,min_height=200|mimes:jpeg,png,jpg,gif,svg',
            'country_ids' => 'required|array|min:2',
            'tags' => 'required|array|min:1',
            'description' => 'required|min:10',
        ]);
        $post = PostModel::query()->find($this->post_id);
        if ($this->image) {
            $image_path = $this->image->store('posts', 'public');
        } else {
            $image_path = $post->image;
        }
        $post->update([
            'title' => $this->title,
            'description' => $this->description,
            'image' => $image_path,
            'comparison_date' => $this->comparison_date,
            'unit' => $this->unit,
        ]);
        $current_p_tag_ids = [];
        $current_p_country_ids = [];

        foreach ($this->tags as $tag) {
            $tag = Tags::query()->firstOrCreate(
                ['name' => $tag, 'slug' => Str::slug($tag)]
            );
            $post_tag = PostTag::query()->firstOrCreate([
                'post_id' => $post->id,
                'tag_id' => $tag->id,
            ]);
            $current_p_tag_ids[] = $post_tag->tag_id;
        }
        foreach ($this->country_ids as $country_id) {
            $m_wage = $this->country_wage[$country_id] ?? null;
            $post_country = PostCountry::query()->updateOrCreate([
                'post_id' => $post->id,
                'country_id' => $country_id
            ], [
                'minimum_wage' => $m_wage
            ]);
            $current_p_country_ids[] = $post_country->country_id;
        }
        PostTag::query()->where('post_id', $post->id)
            ->whereNotIn('tag_id', $current_p_tag_ids)->delete();
        PostCountry::query()->where('post_id', $post->id)
            ->whereNotIn('country_id', $current_p_country_ids)->delete();
        $this->closeModal();
        session()->flash('message', 'Post Updated Successfully.');
        $this->resetInputFields();
    }

    public function delete($id)
    {
        if ($id) {
            PostModel::query()->where('id', $id)->delete();
            session()->flash('message', 'Post Deleted Successfully.');
        }
    }
}
