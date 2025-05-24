<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">Uploaded Tenders</h2>
    </x-slot>

    <div class="p-4">
        <form method="GET" action="{{ route('tenders.index') }}" class="mb-4">
            <label for="tag" class="mr-2">Filter by tag:</label>
            <select name="tag" id="tag" onchange="this.form.submit()" class="border rounded px-2 py-1">
                <option value="">-- All Tags --</option>
                @foreach ($allTags as $tag)
                    <option value="{{ $tag }}" {{ request('tag') === $tag ? 'selected' : '' }}>
                        {{ ucfirst($tag) }}
                    </option>
                @endforeach
            </select>
        </form>

        <table class="w-full border text-sm">
            <thead>
                <tr class="bg-gray-100">
                    <th class="px-4 py-2">#</th>
                    <th class="px-4 py-2">Original Name</th>
                    <th class="px-4 py-2">Uploaded</th>
                    <th class="px-4 py-2">Tags</th>
                    <th class="px-4 py-2">Summary</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($tenders as $tender)
                    <tr class="border-t {{ $tender->is_duplicate ? 'bg-red-100' : '' }}">
                        <td class="px-4 py-2">{{ $loop->iteration }}</td>

                        <td class="px-4 py-2">
                            {{ $tender->original_name }}
                            @if ($tender->is_duplicate)
                                <span class="text-red-600 text-xs font-semibold ml-1">(Duplicate)</span>
                            @endif
                            <br>
                            <a href="{{ route('tenders.export') }}" class="btn btn-success">Export Tenders CSV</a>
                        </td>

                        <td class="px-4 py-2">
                            {{ $tender->created_at->format('d M Y H:i') }}
                        </td>

                        <td class="px-4 py-2">
                            @if ($tender->tags)
                                {{ implode(', ', $tender->tags) }}
                            @else
                                <span class="text-gray-400 italic">None</span>
                            @endif
                            <br>
                            <a href="{{ route('tenders.edit-tags', $tender->id) }}" class="text-blue-500 underline text-xs">
                                Edit Tags
                            </a>
                        </td>

                        <td class="px-4 py-2">
                            {{ $tender->summary ?? '–' }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-2 text-center text-gray-500">
                            No tenders uploaded.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-app-layout>
