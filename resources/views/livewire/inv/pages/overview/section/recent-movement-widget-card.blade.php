<?php

use function Livewire\Volt\{computed, state, placeholder};
use Paparee\BaleInv\App\Models\InventoryItem;
use Paparee\BaleInv\App\Models\InventoryMovement;

$activities = computed(function () {
    return InventoryMovement::orderByDesc('created_at')->limit(5)->get();
});

placeholder('<div class="w-full p-6 mx-auto bg-white shadow animate-pulse rounded-2xl dark:bg-gray-800">
  <h2 class="mb-4 text-lg font-semibold text-gray-800 dark:text-white">Recent Stock Movement Activity</h2>

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
    <h3 class="mb-4 text-lg font-semibold">{{ __('Recent Stock Movement Activity') }}</h3>
    <div class="space-y-4">
        @foreach ($this->activities as $activity)
            <div class="flex items-start">
                @if ($activity->type == 'adjustment' or $activity->type == 'opname')
                    <div class="p-2 mr-3 bg-green-100 rounded-lg dark:bg-green-900/30">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                            stroke-linejoin="round"
                            class="text-green-600 lucide lucide-package-plus-icon lucide-package-plus dark:text-green-400">
                            <path d="M16 16h6" />
                            <path d="M19 13v6" />
                            <path
                                d="M21 10V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l2-1.14" />
                            <path d="m7.5 4.27 9 5.15" />
                            <polyline points="3.29 7 12 12 20.71 7" />
                            <line x1="12" x2="12" y1="22" y2="12" />
                        </svg>
                    </div>
                @elseif ($activity->type == 'distribution')
                    <div class="p-2 mr-3 bg-blue-100 rounded-lg dark:bg-blue-900/30">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                            stroke-linejoin="round"
                            class="text-blue-600 lucide lucide-package-open-icon lucide-package-open dark:text-blue-400">
                            <path d="M12 22v-9" />
                            <path
                                d="M15.17 2.21a1.67 1.67 0 0 1 1.63 0L21 4.57a1.93 1.93 0 0 1 0 3.36L8.82 14.79a1.655 1.655 0 0 1-1.64 0L3 12.43a1.93 1.93 0 0 1 0-3.36z" />
                            <path
                                d="M20 13v3.87a2.06 2.06 0 0 1-1.11 1.83l-6 3.08a1.93 1.93 0 0 1-1.78 0l-6-3.08A2.06 2.06 0 0 1 4 16.87V13" />
                            <path
                                d="M21 12.43a1.93 1.93 0 0 0 0-3.36L8.83 2.2a1.64 1.64 0 0 0-1.63 0L3 4.57a1.93 1.93 0 0 0 0 3.36l12.18 6.86a1.636 1.636 0 0 0 1.63 0z" />
                        </svg>
                    </div>
                @elseif ($activity->type == 'return')
                    <div class="p-2 mr-3 bg-yellow-100 rounded-lg dark:bg-yellow-900/30">

                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                            stroke-linejoin="round"
                            class="text-yellow-600 lucide lucide-arrow-down-to-dot-icon lucide-arrow-down-to-dot dark:text-yellow-400">
                            <path d="M12 2v14" />
                            <path d="m19 9-7 7-7-7" />
                            <circle cx="12" cy="21" r="1" />
                        </svg>
                    </div>
                @elseif ($activity->type == 'damaged' or $activity->type == 'missing')
                    <div class="p-2 mr-3 bg-red-100 rounded-lg dark:bg-red-900/30">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                            stroke-linejoin="round"
                            class="text-red-600 lucide lucide-package-x-icon lucide-package-x dark:text-red-400">
                            <path
                                d="M21 10V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l2-1.14" />
                            <path d="m7.5 4.27 9 5.15" />
                            <polyline points="3.29 7 12 12 20.71 7" />
                            <line x1="12" x2="12" y1="22" y2="12" />
                            <path d="m17 13 5 5m-5 0 5-5" />
                        </svg>
                    </div>
                @else
                    {{-- missing --}}
                    <div class="p-2 mr-3 bg-gray-100 rounded-lg dark:bg-gray-900/30">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                            stroke-linejoin="round"
                            class="text-gray-500 lucide lucide-circle-alert-icon lucide-circle-alert dark:text-gray-400">
                            <circle cx="12" cy="12" r="10" />
                            <line x1="12" x2="12" y1="8" y2="12" />
                            <line x1="12" x2="12.01" y1="16" y2="16" />
                        </svg>
                    </div>
                @endif
                <div>
                    <p class="font-medium">{{ $activity->item_name }}</p>
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        {{ $activity->direction === 'in' ? 'Checked in' : 'Checked out' }} by
                        {{ $activity->user->name ?? '-' }}
                    </p>
                    <p class="mt-1 text-xs text-gray-400 dark:text-gray-500">
                        {{ $activity->created_at->diffForHumans() }}</p>
                </div>
            </div>
        @endforeach
        {{-- <div class="flex items-start">
            <div class="p-2 mr-3 bg-red-100 rounded-lg dark:bg-red-900/30">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-red-600 dark:text-red-400" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </div>
            <div>
                <p class="font-medium">Monitor #INV-2023-0789</p>
                <p class="text-sm text-gray-500 dark:text-gray-400">Checked out to Sarah Smith</p>
                <p class="mt-1 text-xs text-gray-400 dark:text-gray-500">5 hours ago</p>
            </div>
        </div>
        <div class="flex items-start">
            <div class="p-2 mr-3 bg-green-100 rounded-lg dark:bg-green-900/30">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-green-600 dark:text-green-400"
                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 13l4 4L19 7" />
                </svg>
            </div>
            <div>
                <p class="font-medium">Chair #INV-2022-1234</p>
                <p class="text-sm text-gray-500 dark:text-gray-400">Returned to inventory</p>
                <p class="mt-1 text-xs text-gray-400 dark:text-gray-500">1 day ago</p>
            </div>
        </div>
        <div class="flex items-start">
            <div class="p-2 mr-3 bg-red-100 rounded-lg dark:bg-red-900/30">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-red-600 dark:text-red-400" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M6 18L18 6M6 6l12 12" />
                </svg>
            </div>
            <div>
                <p class="font-medium">Software License #INV-2023-1122</p>
                <p class="text-sm text-gray-500 dark:text-gray-400">Assigned to IT Department</p>
                <p class="mt-1 text-xs text-gray-400 dark:text-gray-500">2 days ago</p>
            </div>
        </div>
        <div class="flex items-start">
            <div class="p-2 mr-3 bg-yellow-100 rounded-lg dark:bg-yellow-900/30">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-yellow-600 dark:text-yellow-400"
                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
            </div>
            <div>
                <p class="font-medium">Printer #INV-2021-0456</p>
                <p class="text-sm text-gray-500 dark:text-gray-400">Marked as damaged</p>
                <p class="mt-1 text-xs text-gray-400 dark:text-gray-500">3 days ago</p>
            </div>
        </div> --}}
    </div>
</div>
