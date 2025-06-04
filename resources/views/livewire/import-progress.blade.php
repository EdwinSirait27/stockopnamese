<div wire:poll.1000ms="polling">
    @if ($done)
        <div class="text-green-600 font-bold">Import selesai! (100%)</div>
    @else
        <div class="text-blue-600 font-bold">Progress: {{ $progress }}%</div>
        <div class="w-full bg-gray-200 rounded-full h-4 mt-2">
            <div class="bg-blue-500 h-4 rounded-full" style="width: {{ $progress }}%"></div>
        </div>
    @endif
</div>
