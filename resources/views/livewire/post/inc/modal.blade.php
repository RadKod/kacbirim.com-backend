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
                            <input type="text" id="tags" placeholder="tags">
                        </div>
                        @error('tags') <span class="text-danger">{{ $message }}</span>@enderror
                    </div>

                    <div class="form-group">
                        <div wire:ignore>
                            <label for="country_ids">Countries</label>
                            <input type="text" id="country_ids" class="countries" placeholder="countries">
                        </div>
                        @error('country_ids') <span class="text-danger">{{ $message }}</span>@enderror
                    </div>

                    {{--<div class="form-group">
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
                    </div>--}}

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

    @push('ex_scripts')
        <script>
            $(document).ready(function () {
                const tag_input = document.getElementById('tags');
                const country_input = document.getElementById('country_ids');

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
                    console.log(e.detail.data)
                    @this.call('remove_city_id', e.detail.data.id);
                    @this.call('remove_city_name', e.detail.data.value);
                });

                tagify_country.on('add', function (e) {
                    @this.call('add_city_id', e.detail.data.id);
                    @this.call('add_city_name', e.detail.data.value);
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
                        tagify_country.settings.whitelist.push(...result, ...tagify_tag.value)
                        tagify_country
                            .loading(false)
                            .dropdown.show.call(tagify_country, e.detail.value);
                        // tagify_country.settings.whitelist.push(...result, ...tagify_country.value)

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

            .tags-input {
                display: flex;
                flex-wrap: wrap;
                background-color: #fff;
                border-width: 1px;
                border-radius: .25rem;
                padding-left: .5rem;
                padding-right: 1rem;
                padding-top: .5rem;
                padding-bottom: .25rem;
            }

            .tags-input-tag {
                display: inline-flex;
                line-height: 1;
                align-items: center;
                font-size: .875rem;
                background-color: #bcdefa;
                color: #1c3d5a;
                border-radius: .25rem;
                user-select: none;
                padding: .25rem;
                margin-right: .5rem;
                margin-bottom: .25rem;
            }

            .tags-input-tag:last-of-type {
                margin-right: 0;
            }

            .tags-input-remove {
                color: #2779bd;
                font-size: 1.125rem;
                line-height: 1;
            }

            .tags-input-remove:first-child {
                margin-right: .25rem;
            }

            .tags-input-remove:last-child {
                margin-left: .25rem;
            }

            .tags-input-remove:focus {
                outline: 0;
            }

            .tags-input-text {
                flex: 1;
                outline: 0;
                padding-top: .25rem;
                padding-bottom: .25rem;
                margin-left: .5rem;
                margin-bottom: .25rem;
                min-width: 10rem;
            }

            .py-16 {
                padding-top: 4rem;
                padding-bottom: 4rem;
            }
        </style>
    @endpush
</div>
