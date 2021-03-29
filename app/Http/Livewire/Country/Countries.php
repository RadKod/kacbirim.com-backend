<?php

namespace App\Http\Livewire\Country;

use App\Models\CountryWage;
use Livewire\Component;
use App\Models\Countries as CountriesModel;
use Livewire\WithPagination;

class Countries extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $wage_list = [];
    public $wage_delete_list = [];
    public $wage_year, $wage_wage;
    public $name, $code, $currency, $currency_id, $country_id, $confirming;
    public $updateMode = false;

    public function mount()
    {
    }

    public function add_wage(): bool
    {
        $this->validate([
            'wage_year' => 'required|digits:4|integer|min:2021',
            'wage_wage' => 'required|integer',
        ]);

        if ($this->wage_year && $this->wage_wage) {
            foreach ($this->wage_list as $sub_array) {
                if ($sub_array['year'] == $this->wage_year) {
                    $this->addError('wage_year', 'year already added');
                    return false;
                }
            }

            $this->wage_list[] = [
                'year' => $this->wage_year,
                'wage' => $this->wage_wage
            ];
            $this->wage_year = null;
            $this->wage_wage = null;

            return true;
        }
        return false;
    }

    public function delete_wage($key)
    {
        $this->wage_delete_list[] = $this->wage_list[$key];
        unset($this->wage_list[$key]);
    }

    public function undo_wage($key)
    {
        $this->wage_list[] = $this->wage_delete_list[$key];
        unset($this->wage_delete_list[$key]);
    }

    public function render()
    {
        $countries = CountriesModel::query()
            ->orderBy('id', 'desc')->paginate(10);
        return view('livewire.country.countries', [
            'countries' => $countries
        ]);
    }

    private function resetInputFields()
    {
        $this->name = '';
        $this->code = '';
        $this->currency = '';
        $this->currency_id = '';
        $this->wage_list = [];
        $this->wage_delete_list = [];
        $this->wage_year = null;
        $this->wage_wage = null;

        // Clean errors if were visible before
        $this->resetErrorBag();
        $this->resetValidation();
    }

    public function confirmDelete($id)
    {
        $this->confirming = $id;
    }

    public function store()
    {
        $validatedDate = $this->validate([
            'name' => 'required|unique:countries,name',
            'code' => 'required|unique:countries,code',
            'currency' => 'required',
            'currency_id' => 'required'
        ]);

        $country = CountriesModel::query()->create($validatedDate);

        foreach ($this->wage_list as $wage_item) {
            CountryWage::query()->firstOrCreate([
                'country_id' => $country->id,
                'year' => $wage_item['year'],
                'wage' => $wage_item['wage']
            ]);
        }
        session()->flash('message', 'Country Created Successfully.');
        $this->resetInputFields();
        $this->closeModal();
    }

    public function edit($id)
    {
        $this->resetInputFields();
        $this->updateMode = true;
        $country = CountriesModel::query()
            ->with(['country_wages', 'country_wages.country'])
            ->where('id', $id)->first();
        $this->country_id = $id;
        $this->name = $country->name;
        $this->code = $country->code;
        $this->currency = $country->currency;
        $this->currency_id = $country->currency_id;
        foreach ($country->country_wages as $country_wage) {
            $this->wage_list[] = [
                'year' => $country_wage->year,
                'wage' => $country_wage->wage
            ];
        }
    }

    public function cancel()
    {
        $this->updateMode = false;
        $this->resetInputFields();
    }

    public function closeModal()
    {
        $this->updateMode = false;
        $this->emit('closeModal');
        $this->resetInputFields();
    }

    public function openModal()
    {
        $this->country_id = null;
        $this->resetInputFields();
    }

    public function update()
    {
        $validatedData = $this->validate([
            'name' => 'required|unique:countries,name,' . $this->country_id,
            'code' => 'required|unique:countries,code,' . $this->country_id,
            'currency' => 'required',
            'currency_id' => 'required'
        ]);
        $country = CountriesModel::query()->find($this->country_id);
        $country->update($validatedData);
        $current_wage_ids = [];
        foreach ($this->wage_list as $wage_item) {
            $c_w = CountryWage::query()->firstOrCreate([
                'country_id' => $country->id,
                'year' => $wage_item['year'],
                'wage' => $wage_item['wage']
            ]);
            $current_wage_ids[] = $c_w->id;
        }
        CountryWage::query()->where('country_id', $country->id)
            ->whereNotIn('id', $current_wage_ids)->delete();
        $this->closeModal();
        session()->flash('message', 'Country Updated Successfully.');
        $this->resetInputFields();
    }

    /**
     * @param $id
     */
    public function delete($id)
    {
        if ($id) {
            CountriesModel::query()->where('id', $id)->delete();
            session()->flash('message', 'Country Deleted Successfully.');
        }
    }

    public function create_or_update()
    {
        if ($this->country_id) {
            $this->update();
        } else {
            $this->store();
        }
    }
}
