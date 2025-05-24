<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">Upload Tender Document</h2>
    </x-slot>

    <div class="py-6 px-4">
        @if(session('success'))
            <div class="text-green-600">{{ session('success') }}</div>
        @endif

        <form action="{{ route('upload.submit') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="file" name="document" required accept=".pdf,.docx">
            <button type="submit" class="bg-blue-500 text-black px-4 py-2 rounded">Upload</button>
        </form>
    </div>
</x-app-layout>
