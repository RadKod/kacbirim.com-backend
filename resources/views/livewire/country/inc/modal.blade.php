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
                    <div class="row">
                        <div class="col">
                            <input type="hidden" wire:model="country_id">
                            <label for="name">Name</label>
                            <input type="text" class="form-control" wire:model="name" id="name" placeholder="Name">
                            @error('name') <span class="text-danger">{{ $message }}</span>@enderror
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

                    <div class="row">
                        <div class="col">
                            <div class="form-group">
                                <label for="code">alpha-2 Code</label>
                                <input type="text" class="form-control" wire:model="code" id="code"
                                       placeholder="TR, DE, US, ..">
                                @error('code') <span class="text-danger">{{ $message }}</span>@enderror
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group">
                                <label for="currency_id">Currency ISO Code</label>
                                <input type="text" class="form-control" wire:model="currency_id" id="currency_id"
                                       placeholder="Currency id: USD, TRY, ..">
                                @error('currency_id') <span class="text-danger">{{ $message }}</span>@enderror
                            </div>
                        </div>
                    </div>

                    <hr>

                    <fieldset class="country_wage">
                        <legend class="country_wage">country wages</legend>
                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <label for="wage_year">Year</label>
                                    <input type="number" min="1900" max="2100" maxlength="4"
                                           class="form-control" wire:model="wage_year" id="wage_year"
                                           placeholder="Year" name="wage_year">
                                    @error('wage_year') <span class="text-danger">{{ $message }}</span>@enderror

                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label for="wage_wage">Wage</label>
                                    <input type="number" class="form-control" wire:model="wage_wage" id="wage_wage"
                                           placeholder="Wage" name="wage_wage">
                                    @error('wage_wage') <span class="text-danger">{{ $message }}</span>@enderror
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label for="minimum_wage_percentage">Wage percentage</label>
                                    <input type="number" class="form-control" wire:model="minimum_wage_percentage"
                                           id="minimum_wage_percentage"
                                           placeholder="20, 25 (%)" name="wage_wage">
                                    @error('minimum_wage_percentage') <span
                                        class="text-danger">{{ $message }}</span>@enderror
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label for="wage_type">Wage Type</label>
                                    <select name="wage_type" id="wage_type" wire:model="wage_type" class="form-control">
                                        @foreach(\App\Helpers\wage_types() as $wage_id=>$wage_type)
                                            <option value="{{$wage_id}}" {{ $wage_id == 1 ? 'selected' : '' }}>
                                                {{ $wage_type }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('wage_type') <span class="text-danger">{{ $message }}</span>@enderror
                                </div>
                            </div>
                            <div class="col-2">
                                <label for="" class="hidden"></label>
                                <span class="btn btn-success btn-block"
                                      wire:click.prevent="add_wage()">
                                    Add
                                </span>
                            </div>
                        </div>
                        <div class="form-group">
                            <table class="table table-striped table-sm">
                                <thead>
                                <tr>
                                    <th style="width: 22.5%">Year</th>
                                    <th style="width: 22.5%">Wage</th>
                                    <th style="width: 22.5%">Wage Percentage</th>
                                    <th style="width: 22.5%">Wage Type</th>
                                    <th style="width: 10%">Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($wage_list as $wage_key=>$wage_item)
                                    <tr>
                                        <td>{{ $wage_item['year'] }}</td>
                                        <td>{{ $wage_item['wage'] }} {{$currency}}</td>
                                        <td>{{ $wage_item['minimum_wage_percentage'] }}%</td>
                                        <td>{{ \App\Helpers\wage_type_decode($wage_item['wage_type']) }}</td>
                                        <td>
                                            <span wire:click="delete_wage({{ $wage_key }})"
                                                  class="btn btn-sm btn-danger">
                                                delete
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>

                            @if ($wage_delete_list)
                                Deleted wage
                                <table class="table table-striped table-sm">
                                    <thead>
                                    <tr>
                                        <th style="width: 22.5%">Year</th>
                                        <th style="width: 22.5%">Wage</th>
                                        <th style="width: 22.5%">Wage Percentage</th>
                                        <th style="width: 22.5%">Wage Type</th>
                                        <th style="width: 10%">Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($wage_delete_list as $wage_key=>$wage_item)
                                        <tr>
                                            <td>{{ $wage_item['year'] }}</td>
                                            <td>{{ $wage_item['wage'] }} {{$currency}}</td>
                                            <td>{{ $wage_item['minimum_wage_percentage'] }}%</td>
                                            <td>{{ \App\Helpers\wage_type_decode($wage_item['wage_type']) }}</td>
                                            <td>
                                            <span wire:click="undo_wage({{ $wage_key }})"
                                                  class="btn btn-sm btn-primary">
                                                undo
                                            </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            @endif
                        </div>

                    </fieldset>

                </form>
            </div>
            <div class="modal-footer">
                <button type="button" wire:click.prevent="cancel()" class="btn btn-secondary" data-dismiss="modal">
                    Close & Reset
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
    @push('styles')
        <style>
            fieldset.country_wage {
                border: 1px groove #ddd !important;
                padding: 0 1.4em 1.4em 1.4em !important;
                margin: 0 0 1.5em 0 !important;
                -webkit-box-shadow: 0 0 0 0 #000;
                box-shadow: 0 0 0 0 #000;
            }

            legend.country_wage {
                font-size: 1.2em !important;
                font-weight: bold !important;
                text-align: left !important;
                width: auto;
                padding: 0 10px;
                border-bottom: none;
            }
        </style>
    @endpush
</div>
