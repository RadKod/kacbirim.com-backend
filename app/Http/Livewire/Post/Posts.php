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
use function App\Helpers\calculate_purchasing_power;

class Posts extends Component
{
    use WithPagination;
    use WithFileUploads;

    protected $paginationTheme = 'bootstrap';

    public $search_term;
    public $post_image = null;
    public $tags = [];
    public $product = [];
    public $countries = [];
    public $title, $description, $image, $post_id, $confirming, $comparison_date;
    public $updateMode = false;

    public function mount()
    {
    }

    public function render()
    {
        $search_term = '%'.$this->search_term.'%';
        $countries_data = Countries::query()->get();
        $posts = PostModel::query()
            ->where('title', 'like', $search_term)
            ->orderBy('id', 'desc')->paginate(10);
        return view('livewire.post.posts', [
            'countries_data' => $countries_data,
            'posts' => $posts
        ]);
    }

    private function resetInputFields()
    {
        $this->title = null;
        $this->post_image = null;
        $this->description = null;
        $this->image = null;
        $this->post_id = null;
        $this->comparison_date = null;
        $this->confirming = null;
        $this->product = [];
        $this->countries = [];
        $this->tags = [];
        // Clean errors if were visible before
        $this->resetErrorBag();
        $this->resetValidation();
        $this->emit('reset');
    }

    public function calculate_purchasing_power($product_name, $unit, $product_type, $wage)
    {
        $purchasing_power = calculate_purchasing_power($unit, $wage);
        $text = "";
        $year = $purchasing_power['year'];
        $month = $purchasing_power['month'];
        $month_in = $purchasing_power['month_in'];

        if ($month_in) {
            $text = "1 ay içinde " . $month_in . " " . $product_type . " " . $product_name . " alınabilir.";
        } else {
            if ($year) {
                $text = $year . " " . ($month ? 'yıl&nbsp;' : 'yıl\'da ' . $product_name . ' alınabilir.');
            }
            if ($month) {
                $text .= $month . " ay'da " . $product_name . " alınabilir.";
            }
        }
        return $text;
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

    public function add_countries($items)
    {
        $this->countries[] = $items;
    }

    public function remove_countries($items)
    {
        foreach ($this->countries as $key => $country) {
            if ($country['id'] == $items['id']) {
                unset($this->countries[$key]);
                break;
            }
        }
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
            $this->post_image = $post->image;
            $this->comparison_date = $post->comparison_date;

            $new_tags = [];
            $new_countries = [];
            foreach ($post->countries as $country) {
                $this->product[$country->country->id] = [
                    'name' => $country->product_name,
                    'unit' => $country->product_unit,
                    'type' => $country->product_type,
                ];
                $new_countries[] = [
                    'id' => $country->country->id,
                    'value' => $country->country->name,
                    'code' => $country->country->code,
                    'currency' => $country->country->currency,
                    'current_wage' => $country->current_wage_info['wage'],
                ];
            }
            foreach ($post->tags as $tag) {
                $new_tags[] = $tag->tag->name;
            }

            $this->emit('postEdit', [
                'tags' => $new_tags,
                'countries' => $new_countries
            ]);
        }

    }

    public function store()
    {
        $this->validate([
            'title' => 'required|min:6|unique:posts,title',
            'comparison_date' => 'required|date',
            'image' => 'required|dimensions:min_width=100,min_height=200|mimes:jpeg,png,jpg,gif,svg',
            'countries' => 'required|array|min:2',
            'tags' => 'required|array|min:1',
            'description' => 'required|min:10',
        ]);
        $image_path = $this->image->store('posts', 'public');
        $post = PostModel::query()->create([
            'title' => $this->title,
            'slug' => Str::slug($this->title),
            'description' => $this->description,
            'image' => $image_path,
            'comparison_date' => $this->comparison_date
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
        foreach ($this->countries as $country) {
            $m_unit = $this->product[$country['id']] ?? null;
            PostCountry::query()->create([
                'post_id' => $post->id,
                'country_id' => $country['id'],
                'product_name' => $m_unit['name'],
                'product_unit' => $m_unit['unit'],
                'product_type' => $m_unit['type'],
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
            'comparison_date' => 'required|date',
            'image' => 'nullable|dimensions:min_width=100,min_height=200|mimes:jpeg,png,jpg,gif,svg',
            'countries' => 'required|array|min:2',
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
            'comparison_date' => $this->comparison_date
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
        foreach ($this->countries as $country) {
            $m_unit = $this->product[$country['id']] ?? null;
            $post_country = PostCountry::query()->updateOrCreate([
                'post_id' => $post->id,
                'country_id' => $country['id']
            ], [
                'product_name' => $m_unit['name'],
                'product_unit' => $m_unit['unit'],
                'product_type' => $m_unit['type'],
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
