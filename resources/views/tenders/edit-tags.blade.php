<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">Edit Tags</h2>
    </x-slot>

    <div class="p-4 max-w-xl mx-auto">
        <form method="POST" action="{{ route('tenders.update-tags', $tender->id) }}">
            @csrf

            <div class="mb-4">
                <label class="block font-medium text-sm text-gray-700 mb-2">Select Tags:</label>
                @foreach ($allTags as $tag)
                    <label class="inline-flex items-center mr-4 mb-2">
                        <input type="checkbox" name="tags[]" value="{{ $tag }}"
                            {{ in_array($tag, $tender->tags ?? []) ? 'checked' : '' }}>
                        <span class="ml-2">{{ ucfirst($tag) }}</span>
                    </label>
                @endforeach
            </div>

            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">
                Save Tags
            </button>
        </form>
    </div>
</x-app-layout>
