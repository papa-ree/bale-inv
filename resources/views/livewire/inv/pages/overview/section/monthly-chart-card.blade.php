<?php

use Livewire\Volt\Component;

new class extends Component {
    //
}; ?>

<div
    class="p-6 bg-white border border-gray-100 shadow-sm dark:bg-gray-800 rounded-2xl dark:border-gray-700 lg:col-span-2">
    <div class="flex items-center justify-between mb-4">
        <h3 class="text-lg font-semibold">Monthly Stock Movement</h3>
        <select
            class="block w-32 p-2 text-sm text-gray-700 border border-gray-300 rounded-lg bg-gray-50 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200 focus:ring-blue-500 focus:border-blue-500">
            <option selected>Last 6 Months</option>
            <option>Last 12 Months</option>
            <option>This Year</option>
        </select>
    </div>
    <div class="h-64 rounded-lg chart-placeholder"></div>
</div>
