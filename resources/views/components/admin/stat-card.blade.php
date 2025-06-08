<div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
    <div class="p-6">
        <div class="text-gray-500 text-sm mb-1">{{ $title }}</div>
        <div class="text-3xl font-bold text-gray-800">{{ $value }}</div>
        @if(isset($description))
            <div class="text-sm text-gray-500 mt-1">{{ $description }}</div>
        @endif
    </div>
</div> 