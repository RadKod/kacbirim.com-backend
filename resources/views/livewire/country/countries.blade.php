<div>
    <button type="button" class="btn btn-primary" wire:click="openModal()"
            data-toggle="modal" data-target="#crudModal_country">
        Create
    </button>
    @include('livewire.country.inc.modal')
    @if(session()->has('message'))
        <div class="alert {{session('alert') ?? 'alert-info'}}" style="margin-top:30px;">
            {{ session('message') }}
        </div>
    @endif
    <input type="text" wire:model="search_term" class="col-2 form-control mt-3"
           placeholder="Search name.."
    />
    <table class="table table-bordered mt-1">
        <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Code</th>
            <th>Currency</th>
            <th>Currency ISO Code</th>
            <th>Action</th>
        </tr>
        </thead>
        <tbody>
        @foreach($countries as $country)
            <tr>
                <td>{{ $country->id }}</td>
                <td>{{ $country->name }}</td>
                <td>{{ $country->code }}</td>
                <td>{{ $country->currency }}</td>
                <td>{{ $country->currency_id }}</td>
                <td>
                    <button data-toggle="modal" data-target="#crudModal_country" wire:click="edit({{ $country->id }})"
                            class="btn btn-primary btn-sm">Edit
                    </button>
                    @if($confirming===$country->id)
                        <button wire:click="delete({{ $country->id }})" class="btn btn-danger btn-sm">Sure?</button>
                    @else
                        <button wire:click="confirmDelete({{ $country->id }})" class="btn btn-secondary btn-sm">Delete
                        </button>
                    @endif
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
    {{ $countries->links() }}

</div>
