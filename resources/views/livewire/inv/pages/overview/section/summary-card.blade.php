<?php

use function Livewire\Volt\{computed, placeholder};

$stats = computed(function () {
    return cache()->get('bale_cache_inv.dashboard_summary') ?? [];
});

$totalItem = computed(function () {
    return $this->stats['total_items'] ?? [];
});

$activeAssignment = computed(function () {
    return $this->stats['assignments'] ?? [];
});

$damagedMissing = computed(function () {
    return $this->stats['damaged_missing'] ?? [];
});

$movementIn = computed(function () {
    return $this->stats['stock_movement_in'] ?? [];
});

$movementOut = computed(function () {
    return $this->stats['stock_movement_out'] ?? [];
});

placeholder('<div class="grid grid-cols-1 gap-6 mb-8 md:grid-cols-2 lg:grid-cols-4">
    
            <div class="p-6 bg-white border border-gray-100 shadow-sm dark:bg-gray-800 rounded-2xl dark:border-gray-700 animate-pulse">
                <div class="flex items-center justify-between">
                    <div class="flex-1">
                        <div class="w-1/2 h-4 mb-2 bg-gray-200 rounded dark:bg-gray-700"></div>
                        <div class="w-3/4 h-6 bg-gray-300 rounded dark:bg-gray-600"></div>
                    </div>
                    <div class="w-10 h-10 bg-gray-200 rounded-lg dark:bg-gray-700"></div>
                </div>
                <div class="w-2/3 h-3 mt-4 bg-gray-200 rounded dark:bg-gray-600"></div>
            </div>
    
            <div class="p-6 bg-white border border-gray-100 shadow-sm dark:bg-gray-800 rounded-2xl dark:border-gray-700 animate-pulse">
                <div class="flex items-center justify-between">
                    <div class="flex-1">
                        <div class="w-1/2 h-4 mb-2 bg-gray-200 rounded dark:bg-gray-700"></div>
                        <div class="w-3/4 h-6 bg-gray-300 rounded dark:bg-gray-600"></div>
                    </div>
                    <div class="w-10 h-10 bg-gray-200 rounded-lg dark:bg-gray-700"></div>
                </div>
                <div class="w-2/3 h-3 mt-4 bg-gray-200 rounded dark:bg-gray-600"></div>
            </div>
    
            <div class="p-6 bg-white border border-gray-100 shadow-sm dark:bg-gray-800 rounded-2xl dark:border-gray-700 animate-pulse">
                <div class="flex items-center justify-between">
                    <div class="flex-1">
                        <div class="w-1/2 h-4 mb-2 bg-gray-200 rounded dark:bg-gray-700"></div>
                        <div class="w-3/4 h-6 bg-gray-300 rounded dark:bg-gray-600"></div>
                    </div>
                    <div class="w-10 h-10 bg-gray-200 rounded-lg dark:bg-gray-700"></div>
                </div>
                <div class="w-2/3 h-3 mt-4 bg-gray-200 rounded dark:bg-gray-600"></div>
            </div>
    
            <div class="p-6 bg-white border border-gray-100 shadow-sm dark:bg-gray-800 rounded-2xl dark:border-gray-700 animate-pulse">
                <div class="flex items-center justify-between">
                    <div class="flex-1">
                        <div class="w-1/2 h-4 mb-2 bg-gray-200 rounded dark:bg-gray-700"></div>
                        <div class="w-3/4 h-6 bg-gray-300 rounded dark:bg-gray-600"></div>
                    </div>
                    <div class="w-10 h-10 bg-gray-200 rounded-lg dark:bg-gray-700"></div>
                </div>
                <div class="w-2/3 h-3 mt-4 bg-gray-200 rounded dark:bg-gray-600"></div>
            </div>
</div>');

?>

<div>
    <div class="grid grid-cols-1 gap-6 mb-8 md:grid-cols-2 lg:grid-cols-4">
        @if (empty($this->stats))
            Summary No Data
        @else
            {{-- <!-- Total Items --> --}}
            <div class="p-6 bg-white border border-gray-100 shadow-sm dark:bg-gray-800 rounded-2xl dark:border-gray-700">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Items</p>
                        <h3 class="mt-1 text-2xl font-bold">{{ $this->totalItem['value'] }}</h3>
                    </div>
                    <div class="p-3 rounded-lg bg-blue-50 dark:bg-blue-900/30">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-blue-500" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                        </svg>
                    </div>
                </div>
                <p class="mt-4 text-sm text-gray-500 dark:text-gray-400">
                    <span class="font-medium text-green-500">{{ $this->totalItem['change'] }}%</span> from last month
                </p>
            </div>

            {{-- <!-- Active Assignments --> --}}
            <div
                class="p-6 bg-white border border-gray-100 shadow-sm dark:bg-gray-800 rounded-2xl dark:border-gray-700">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Active Assignments</p>
                        <h3 class="mt-1 text-2xl font-bold">{{ $this->activeAssignment['value'] }}</h3>
                    </div>
                    <div class="p-3 rounded-lg bg-green-50 dark:bg-green-900/30">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-green-500" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                    </div>
                </div>
                <p class="mt-4 text-sm text-gray-500 dark:text-gray-400">
                    <span class="font-medium text-green-500">{{ $this->activeAssignment['change'] }}%</span> from last
                    month
                </p>
            </div>

            {{-- <!-- Damaged or Missing Items --> --}}
            <div
                class="p-6 bg-white border border-gray-100 shadow-sm dark:bg-gray-800 rounded-2xl dark:border-gray-700">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Damaged/Missing</p>
                        <h3 class="mt-1 text-2xl font-bold">{{ $this->damagedMissing['value'] }}</h3>
                    </div>
                    <div class="p-3 rounded-lg bg-red-50 dark:bg-red-900/30">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-red-500" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>
                </div>
                <p class="mt-4 text-sm text-gray-500 dark:text-gray-400">
                    <span class="font-medium text-green-500">{{ $this->damagedMissing['change'] }}%</span> from last
                    month
                </p>
            </div>

            {{-- <!-- Stock Movement --> --}}
            <div
                class="p-6 bg-white border border-gray-100 shadow-sm dark:bg-gray-800 rounded-2xl dark:border-gray-700">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Stock Movement</p>
                        <h3 class="mt-1 text-2xl font-bold">+{{ $this->movementIn['value'] }} /
                            -{{ $this->movementOut['value'] }}</h3>
                    </div>
                    <div class="p-3 rounded-lg bg-blue-50 dark:bg-blue-900/30">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-blue-500" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                        </svg>
                    </div>
                </div>
                <p class="mt-4 text-sm text-gray-500 dark:text-gray-400">
                    <span class="font-medium text-green-500">+In / -Out</span>
                </p>
            </div>
        @endif
    </div>
</div>
