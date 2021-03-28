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
                <ul>
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
                        <div class="col-6">
                            <label for="title">Name</label>
                            <input type="text" class="form-control" id="title" wire:model="title" placeholder="Title">
                            @error('title') <span class="text-danger">{{ $message }}</span>@enderror
                        </div>
                        <div class="col-6">
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
                            <input type="text" id="tags" placeholder="tags">
                        </div>
                        @error('tags') <span class="text-danger">{{ $message }}</span>@enderror
                    </div>

                    <div class="form-group">
                        <div wire:ignore>
                            <label for="countries">Countries</label>
                            <input type="text" id="countries" class="countries" placeholder="countries">
                        </div>
                        @error('countries') <span class="text-danger">{{ $message }}</span>@enderror
                    </div>

                    @if($countries)
                        <fieldset class="post_salary">
                            <legend class="post_salary">product unit</legend>
                            @foreach($countries as $key=>$country)
                                <div class="card mb-3">
                                    <div class="card-body">
                                        <div class="form-group">
                                            <div class="form-group">
                                                <label for="product_{{$key}}">
                                                    {{ $country['value'] }}
                                                    (wage: {{ $country['current_wage'] }}{{ $country['currency'] }})
                                                </label>
                                                <input type="text" class="form-control" id="product_{{$key}}"
                                                       wire:model="product.{{$country['id']}}.name" name="product[]"
                                                       placeholder="product name">
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <div class="col-6">
                                                <input type="number" class="form-control" id="product_unit_{{$key}}"
                                                       wire:model="product.{{$country['id']}}.unit" name="product[]"
                                                       placeholder="unit price">
                                            </div>
                                            <div class="col-6">
                                                <input type="text" class="form-control" id="product_type_{{$key}}"
                                                       wire:model="product.{{$country['id']}}.type" name="product[]"
                                                       placeholder="product type: kilogram, adet, litre, ...">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </fieldset>

                        <ul>
                            @foreach ($countries as $country)
                                @if (key_exists($country['id'], $product))
                                    @if(key_exists('name', $product[$country['id']]) and key_exists('unit', $product[$country['id']]) and key_exists('type', $product[$country['id']]))
                                        <li>
                                            {{ $country['value'] }} : {!! $this->calculate_purchasing_power(
                                                                $product[$country['id']]['name'], $product[$country['id']]['unit'], $product[$country['id']]['type'], $country['current_wage']
                                                                ) !!}
                                        </li>
                                    @endif
                                @endif
                            @endforeach
                        </ul>
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

    @push('ex_scripts')
        <script>
            $(document).ready(function () {
                const tag_input = document.getElementById('tags');
                const country_input = document.getElementById('countries');

                const tagify_tag = new Tagify(tag_input, {
                    enforceWhitelist: false,
                    whitelist: tag_input.value.trim().split(/\s*,\s*/)
                })
                const tagify_country = new Tagify(country_input, {
                    delimiters: null,
                    enforceWhitelist: true,
                    whitelist: country_input.value.trim().split(/\s*,\s*/)
                });

                tagify_country.on('remove', function (e) {
                @this.call('remove_countries', e.detail.data);
                });

                tagify_country.on('add', function (e) {
                @this.call('add_countries', e.detail.data);
                });

                tagify_country.on('input', function (e) {
                    tagify_country.settings.whitelist.length = 0;
                    tagify_country.loading(true)

                    $.ajax({
                        url: "{{ route('country.search') }}",
                        data: {
                            'term': e.detail.value
                        }
                    }).then(function (result) {
                        tagify_country.settings.whitelist.push(...result, ...tagify_country.value)
                        tagify_country
                            .loading(false)
                            .dropdown.show.call(tagify_country, e.detail.value);

                    }).catch(err => tagify_country.dropdown.hide.call(tagify_country))
                });

                tagify_tag.on('add', onAddTag)
                    .on('remove', onRemoveTag)
                    .on('input', onInput);

                function onAddTag(e) {
                @this.call('add_tag', e.detail.data.value);
                }

                function onRemoveTag(e) {
                @this.call('remove_tag', e.detail.data.value);
                }

                function onInput(e) {
                    tagify_tag.settings.whitelist.length = 0;
                    tagify_tag.loading(true)

                    $.ajax({
                        url: "{{ route('tags.search') }}",
                        data: {
                            'term': e.detail.value
                        }
                    }).then(function (result) {
                        tagify_tag.settings.whitelist.push(...result, ...tagify_tag.value)
                        tagify_tag
                            .loading(false)
                            .dropdown.show.call(tagify_tag, e.detail.value);
                    }).catch(err => tagify_tag.dropdown.hide.call(tagify_tag))
                }

                window.livewire.on('closeModal', () => {
                    $('#crudModal_post').modal('hide');
                    tagify_tag.removeAllTags()
                    tagify_country.removeAllTags()
                });
                window.livewire.on('reset', () => {
                    tagify_tag.removeAllTags()
                    tagify_country.removeAllTags()
                });
                window.livewire.on('postEdit', items => {
                    tagify_country.settings.whitelist.push(...items['countries'], ...tagify_country.value)
                    tagify_country.addTags(items['countries'])
                    tagify_tag.addTags(items['tags'])
                })
            });
        </script>
    @endpush

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
</div>
