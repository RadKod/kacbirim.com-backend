<div wire:ignore.self class="modal fade" id="crudModal_country" tabindex="-1" role="dialog"
     aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Country</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form>
                    <div class="form-group">
                        <input type="hidden" wire:model="country_id">
                        <label for="name">Name</label>
                        <input type="text" class="form-control" wire:model="name" id="name" placeholder="Name">
                        @error('name') <span class="text-danger">{{ $message }}</span>@enderror
                    </div>
                    <div class="row">
                        <div class="col">
                            <div class="form-group">
                                <label for="code">Code</label>
                                <input type="text" class="form-control" wire:model="code" id="code" placeholder="Code">
                                @error('code') <span class="text-danger">{{ $message }}</span>@enderror
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group">
                                <label for="currency">Currency symbol</label>
                                <input type="text" class="form-control" wire:model="currency" id="currency"
                                       placeholder="Currency">
                                @error('currency') <span class="text-danger">{{ $message }}</span>@enderror
                            </div>
                        </div>
                    </div>


                </form>
            </div>
            <div class="modal-footer">
                <button type="button" wire:click.prevent="cancel()" class="btn btn-secondary" data-dismiss="modal">
                    Close
                </button>
                <button type="button" wire:click.prevent="create_or_update()" class="btn btn-primary">Save changes
                </button>
            </div>
        </div>
    </div>
    @push('ex_scripts')
        <script type="text/javascript">
            window.livewire.on('closeModal', () => {
                $('#crudModal_country').modal('hide');
            });
        </script>
    @endpush
</div>
