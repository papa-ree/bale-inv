<?php

use function Livewire\Volt\{computed, state, placeholder};
use Paparee\BaleInv\App\Models\InventoryItem;
use Paparee\BaleInv\App\Models\InventoryAssignment;

$activities = computed(function () {
    return InventoryAssignment::orderByDesc('updated_at')->limit(5)->get();
});

placeholder('<div class="w-full p-6 mx-auto bg-white shadow animate-pulse rounded-2xl dark:bg-gray-800">
  <h2 class="mb-4 text-lg font-semibold text-gray-800 dark:text-white">Recent Assignment Activity</h2>

  <!-- Skeleton items -->
  <div class="space-y-4">
    <!-- Item skeleton -->
    <div class="flex space-x-4">
      <div class="w-10 h-10 bg-gray-300 rounded-full dark:bg-gray-700"></div>
      <div class="flex-1 py-1 space-y-2">
        <div class="w-3/4 h-4 bg-gray-300 rounded dark:bg-gray-700"></div>
        <div class="w-1/2 h-3 bg-gray-200 rounded dark:bg-gray-600"></div>
        <div class="w-1/3 h-2 bg-gray-200 rounded dark:bg-gray-600"></div>
      </div>
    </div>

    <div class="flex space-x-4">
      <div class="w-10 h-10 bg-gray-300 rounded-full dark:bg-gray-700"></div>
      <div class="flex-1 py-1 space-y-2">
        <div class="w-3/4 h-4 bg-gray-300 rounded dark:bg-gray-700"></div>
        <div class="w-1/2 h-3 bg-gray-200 rounded dark:bg-gray-600"></div>
        <div class="w-1/3 h-2 bg-gray-200 rounded dark:bg-gray-600"></div>
      </div>
    </div>

    <div class="flex space-x-4">
      <div class="w-10 h-10 bg-gray-300 rounded-full dark:bg-gray-700"></div>
      <div class="flex-1 py-1 space-y-2">
        <div class="w-3/4 h-4 bg-gray-300 rounded dark:bg-gray-700"></div>
        <div class="w-1/2 h-3 bg-gray-200 rounded dark:bg-gray-600"></div>
        <div class="w-1/3 h-2 bg-gray-200 rounded dark:bg-gray-600"></div>
      </div>
    </div>
  </div>
</div>
');

?>

<div class="p-6 bg-white border border-gray-100 shadow-sm dark:bg-gray-800 rounded-2xl dark:border-gray-700">
    <h3 class="mb-4 text-lg font-semibold">{{ __('Recent Assignment Activity') }}</h3>
    <div class="space-y-4">
        @foreach ($this->activities as $activity)
            <div class="flex items-start">
                @if ($activity->type == 'distribution')
                    <div class="p-2 mr-3 rounded-lg bg-emerald-100 dark:bg-emerald-900/30">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round"
                            class="text-emerald-600 dark:text-emerald-400 lucide lucide-arrow-up-right-icon lucide-arrow-up-right">
                            <path d="M7 7h10v10" />
                            <path d="M7 17 17 7" />
                        </svg>
                    </div>
                @elseif ($activity->type == 'return' && $activity->return_condition == 'damaged')
                    {{-- missing --}}
                    <div class="p-2 mr-3 bg-red-100 rounded-lg dark:bg-red-900/30">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round"
                            class="text-red-600 dark:text-red-400 lucide lucide-triangle-alert-icon lucide-triangle-alert">
                            <path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3" />
                            <path d="M12 9v4" />
                            <path d="M12 17h.01" />
                        </svg>
                    </div>
                @else
                    <div class="p-2 mr-3 bg-yellow-100 rounded-lg dark:bg-yellow-900/30">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round"
                            class="text-yellow-600 dark:text-yellow-400 lucide lucide-undo2-icon lucide-undo-2">
                            <path d="M9 14 4 9l5-5" />
                            <path d="M4 9h10.5a5.5 5.5 0 0 1 5.5 5.5a5.5 5.5 0 0 1-5.5 5.5H11" />
                        </svg>
                    </div>
                @endif
                <div>
                    <p class="font-medium">
                        {{ $activity->item_name }}
                    </p>
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        @if ($activity->type == 'return' && $activity->return_condition == 'damaged')
                            {{-- <span
                                class="inline-block px-2 py-1 text-xs font-medium text-yellow-800 truncate bg-yellow-100 rounded-lg max-w-40 whitespace-nowrap dark:bg-yellow-800/30 dark:text-yellow-500">{{ __('Mark as damaged') }}</span> --}}
                            {{ __('Marked as damaged') }}
                        @else
                            {{ $activity->is_returned ? 'Return' : 'Distribute' }} by
                            {{ $activity->user->name ?? '-' }}
                        @endif
                    </p>
                    <p class="mt-1 text-xs text-gray-400 dark:text-gray-500">
                        {{ $activity->updated_at->diffForHumans() }}
                    </p>
                </div>
            </div>
        @endforeach
    </div>
</div>
