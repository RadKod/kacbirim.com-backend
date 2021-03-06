<div wire:ignore.self class="modal fade" id="crudModal_post" tabindex="-1" role="dialog"
     aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Post</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form>
                    <input type="hidden" wire:model="post_id">

                    <div class="form-row">
                        <div class="col-12">
                            <label class="block font-medium text-sm text-gray-700" for="image">
                                Post Image
                            </label>

                            <input type="file" wire:model="image" id="image"
                                   class="form-control" placeholder="Post Image">
                            @error('image') <span class="text-danger">{{ $message }}</span>@enderror

                        </div>

                        @if ($image and !$errors->has('image'))
                            <div class="col-12 text-center">
                                <img src="{{ $image->temporaryUrl() }}" style="height: 300px;object-fit: contain;"
                                     alt="post image">
                            </div>
                        @else
                            @if($post_image)
                                <div class="col-12 text-center">
                                    <img src="{{ asset('storage/'.$post_image) }}" alt="post image"
                                         style="height: 300px;object-fit: contain;">
                                </div>
                            @endif
                        @endif
                    </div>

                    <div class="form-row">
                        <div class="col-4">
                            <label for="title">Name</label>
                            <input type="text" class="form-control" id="title" wire:model="title" placeholder="Title">
                            @error('title') <span class="text-danger">{{ $message }}</span>@enderror
                        </div>
                        <div class="col-4">
                            <label for="unit">Unit</label>
                            <input type="number" class="form-control" id="unit" wire:model="unit" placeholder="Unit">
                            @error('unit') <span class="text-danger">{{ $message }}</span>@enderror
                        </div>
                        <div class="col-4">
                            <label for="comparison_date">Comparison Date</label>
                            <input type="date" class="form-control" id="comparison_date" wire:model="comparison_date"
                                   placeholder="Comparison Date">
                            @error('comparison_date') <span class="text-danger">{{ $message }}</span>@enderror
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea wire:model="description" id="description" class="form-control"></textarea>
                        @error('description') <span class="text-danger">{{ $message }}</span>@enderror
                    </div>

                    <div class="form-group">
                        <div wire:ignore>
                            <label for="tags">Tags</label>
                            <select wire:model="tags" name="tags[]" id="tags" class="form-control" multiple
                                    style="width: 100%"></select>
                        </div>
                        @error('tags') <span class="text-danger">{{ $message }}</span>@enderror
                    </div>

                    <div class="form-group">
                        <div wire:ignore>
                            <label for="country_ids">Countries</label>
                            <select wire:model="country_ids" name="country_ids[]" id="country_ids" class="form-control"
                                    multiple style="width: 100%">
                                @foreach($countries_data as $country)
                                    <option value="{{$country->id}}">{{$country->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        @error('country_ids') <span class="text-danger">{{ $message }}</span>@enderror
                    </div>

                    @if($country_ids)
                        <fieldset class="post_salary">
                            <legend class="post_salary">monthly salary</legend>
                            @foreach($country_ids as $key=>$country_id)
                                <div class="form-group">
                                    <label for="monthly_salary_{{$key}}">{{ $country_names[$key] }}</label>
                                    <input type="number" class="form-control" id="monthly_salary_{{$key}}"
                                           wire:model="country_wage.{{$country_id}}" name="country_wage[]"
                                           placeholder="monthly salary">
                                </div>
                            @endforeach
                        </fieldset>
                    @endif
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
    @push('styles')
        <style>
            fieldset.post_salary {
                border: 1px groove #ddd !important;
                padding: 0 1.4em 1.4em 1.4em !important;
                margin: 0 0 1.5em 0 !important;
                -webkit-box-shadow: 0 0 0 0 #000;
                box-shadow: 0 0 0 0 #000;
            }

            legend.post_salary {
                font-size: 1.2em !important;
                font-weight: bold !important;
                text-align: left !important;
                width: auto;
                padding: 0 10px;
                border-bottom: none;
            }
        </style>
    @endpush
    @push('ex_scripts')
        <script>
            $(document).ready(function () {
                let s2_countries = $('#country_ids');
                let s2_tags = $('#tags');

                s2_countries.select2({width: 'resolve'});
                s2_tags.select2({
                    width: 'resolve',
                    tags: true,
                    multiple: true,
                    minimumInputLength: 1,
                    placeholder: "Select a tags",
                    ajax: {
                        url: "{{ route('tags.search') }}",
                        dataType: 'json',
                        delay: 250,
                        type: "GET",
                    }
                });

                s2_tags.on('change', function () {
                    let tags = [];
                    const items = s2_tags.select2("data");
                    items.forEach(function (item) {
                        tags.push(item.text);
                    });
                    @this.set('tags', tags);
                });
                s2_countries.on('change', function () {
                    let country_ids = [];
                    let country_names = [];
                    const items = s2_countries.select2("data");
                    items.forEach(function (item) {
                        country_ids.push(item.id);
                        country_names.push(item.text);
                    });
                    @this.set('country_ids', country_ids);
                    @this.set('country_names', country_names);
                });
                window.livewire.on('closeModal', () => {
                    $('#crudModal_post').modal('hide');
                });
                window.livewire.on('reset', () => {
                    s2_countries.val(null).trigger('change');
                    s2_tags.val(null).trigger('change');
                });
                window.livewire.on('postEdit', items => {
                    // console.log(items)
                    items['tags'].forEach(function (item){
                        const option = new Option(item['text'], item['id'], true, true);
                        // console.log(option)
                        // s2_tags.append(option).trigger('change');
                        // s2_tags.select2({data: items['tags']});
                    });
                    // s2_tags.val(items['tag_ids']).trigger('change');
                    s2_countries.val(items['countries']).trigger('change');
                })
            });
        </script>
    @endpush
</div>
