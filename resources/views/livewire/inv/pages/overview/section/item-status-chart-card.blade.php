<?php

use Livewire\Volt\Component;

new class extends Component {
    //
}; ?>

<div class="p-6 bg-white border border-gray-100 shadow-sm dark:bg-gray-800 rounded-2xl dark:border-gray-700">
    <h3 class="mb-4 text-lg font-semibold">Item Statuses</h3>
    <div class="h-64 rounded-lg chart-placeholder"></div>
    <div class="grid grid-cols-2 gap-4 mt-4">
        <div class="flex items-center">
            <div class="w-3 h-3 mr-2 bg-green-500 rounded-full"></div>
            <span class="text-sm">Active (76%)</span>
        </div>
        <div class="flex items-center">
            <div class="w-3 h-3 mr-2 bg-blue-500 rounded-full"></div>
            <span class="text-sm">Loaned (18%)</span>
        </div>
        <div class="flex items-center">
            <div class="w-3 h-3 mr-2 bg-yellow-500 rounded-full"></div>
            <span class="text-sm">Damaged (4%)</span>
        </div>
        <div class="flex items-center">
            <div class="w-3 h-3 mr-2 bg-red-500 rounded-full"></div>
            <span class="text-sm">Lost (2%)</span>
        </div>
    </div>
</div>
