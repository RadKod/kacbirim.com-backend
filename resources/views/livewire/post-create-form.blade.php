<form wire:submit.prevent="savePost">
    <div class="grid grid-cols-1 gap-6">
        <div class="col-span-6 sm:col-span-4">
            <label class="block font-medium text-sm text-gray-700" for="title">
                Post Title
            </label>

            <input type="text" wire:model="title" id="title"
                   class="border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm mt-1 block w-full
@error('title')border-red-500 @enderror" placeholder="Post Title">
            @error('title') <span class="error text-red-500">{{ $message }}</span>@enderror

        </div>
        <div class="col-span-6 sm:col-span-4">
            <label class="block font-medium text-sm text-gray-700" for="countries">
                Post Countries
            </label>
            <select wire:model="countries" name="countries[]" id="countries" multiple class="border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm mt-1 block w-full
@error('countries')border-red-500 @enderror">
                @foreach($countries_data as $country)
                    <option value="{{$country->id}}">{{$country->name}}</option>
                @endforeach
            </select>
            @error('countries') <span class="error text-red-500">{{ $message }}</span>@enderror
        </div>
    </div>

    <div class="flex items-center justify-end px-4 py-3 text-right">
        <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:shadow-outline-gray disabled:opacity-25 transition ease-in-out duration-150">
            Save Post
        </button>
    </div>
</form>
