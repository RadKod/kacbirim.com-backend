<?php

namespace App\Http\Livewire\Country;

use Livewire\Component;
use App\Models\Countries as CountriesModel;
use Livewire\WithPagination;

class Countries extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public $name, $code, $currency, $country_id, $confirming;
    public $updateMode = false;

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
            'currency' => 'required'
        ]);

        CountriesModel::query()->create($validatedDate);
        session()->flash('message', 'Country Created Successfully.');
        $this->resetInputFields();
        $this->closeModal();
    }

    public function edit($id)
    {
        $this->updateMode = true;
        $country = CountriesModel::query()->where('id', $id)->first();
        $this->country_id = $id;
        $this->name = $country->name;
        $this->code = $country->code;
        $this->currency = $country->currency;
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
        // Clean errors if were visible before
        $this->resetErrorBag();
        $this->resetValidation();
    }

    public function update()
    {
        $validatedData = $this->validate([
            'name' => 'required|unique:countries,name,' . $this->country_id,
            'code' => 'required|unique:countries,code,' . $this->country_id,
            'currency' => 'required'
        ]);
        $user = CountriesModel::query()->find($this->country_id);
        $user->update($validatedData);
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

    public function create_or_update(){
        if ($this->country_id){
            $this->update();
        }else{
            $this->store();
        }
    }
}
