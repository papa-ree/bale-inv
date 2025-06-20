<?php

use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;
use function Livewire\Volt\{title, mount};

title('INV | Stock Item');

mount(function () {
    if (session()->has('saved')) {
        LivewireAlert::title(session('saved.title'))->toast()->position('top-end')->success()->show();
    }
});

?>

<div>
    <div class="min-h-screen">

        <!-- Main Content -->
        <main class="px-4 py-6 mx-auto sm:px-6 lg:px-8">
            <!-- Page Title -->
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-2xl font-bold">{{ __('Inventory Overview') }}</h2>
                <div class="flex space-x-2">
                    <x-bale.button label="Add Item" type="button" link href="{{ route('items.create', 'new') }}" />
                </div>
            </div>

            {{-- <!-- Summary Cards --> --}}
            <livewire:inv.pages.overview.section.summary-card lazy="on-load" />


            {{-- <!-- Charts Section -->
            <div class="grid grid-cols-1 gap-6 mb-8 lg:grid-cols-3">
                <!-- Monthly Stock Movement -->
                <livewire:inv.pages.overview.section.monthly-chart-card />

                <!-- Item Statuses -->
                <livewire:inv.pages.overview.section.item-status-chart-card />

            </div> --}}

            <!-- Bottom Section -->
            <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
                <!-- Recent Movement Activity -->
                <livewire:inv.pages.overview.section.recent-activity-widget-card lazy="on-load" />
                {{-- recent assignment --}}
                <livewire:inv.pages.overview.section.recent-assign-widget-card lazy="on-load" />

                {{-- <!-- Warranty Expiring -->
                <div
                    class="p-6 bg-white border border-gray-100 shadow-sm dark:bg-gray-800 rounded-2xl dark:border-gray-700">
                    <h3 class="mb-4 text-lg font-semibold">Warranty Expiring</h3>
                    <div class="space-y-4">
                        <div class="flex items-start">
                            <div class="p-2 mr-3 bg-orange-100 rounded-lg dark:bg-orange-900/30">
                                <svg xmlns="http://www.w3.org/2000/svg"
                                    class="w-5 h-5 text-orange-600 dark:text-orange-400" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div>
                                <p class="font-medium">Server #INV-2020-0789</p>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Warranty ends in 15 days</p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <div class="p-2 mr-3 bg-orange-100 rounded-lg dark:bg-orange-900/30">
                                <svg xmlns="http://www.w3.org/2000/svg"
                                    class="w-5 h-5 text-orange-600 dark:text-orange-400" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div>
                                <p class="font-medium">Laptop #INV-2021-1122</p>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Warranty ends in 22 days</p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <div class="p-2 mr-3 bg-orange-100 rounded-lg dark:bg-orange-900/30">
                                <svg xmlns="http://www.w3.org/2000/svg"
                                    class="w-5 h-5 text-orange-600 dark:text-orange-400" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div>
                                <p class="font-medium">Monitor #INV-2021-0456</p>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Warranty ends in 30 days</p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <div class="p-2 mr-3 bg-orange-100 rounded-lg dark:bg-orange-900/30">
                                <svg xmlns="http://www.w3.org/2000/svg"
                                    class="w-5 h-5 text-orange-600 dark:text-orange-400" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div>
                                <p class="font-medium">Projector #INV-2022-0789</p>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Warranty ends in 45 days</p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <div class="p-2 mr-3 bg-orange-100 rounded-lg dark:bg-orange-900/30">
                                <svg xmlns="http://www.w3.org/2000/svg"
                                    class="w-5 h-5 text-orange-600 dark:text-orange-400" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div>
                                <p class="font-medium">Switch #INV-2022-1122</p>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Warranty ends in 60 days</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Maintenance Reminder -->
                <div
                    class="p-6 bg-white border border-gray-100 shadow-sm dark:bg-gray-800 rounded-2xl dark:border-gray-700">
                    <h3 class="mb-4 text-lg font-semibold">Maintenance This Week</h3>
                    <div class="space-y-4">
                        <div class="flex items-start">
                            <div class="p-2 mr-3 bg-purple-100 rounded-lg dark:bg-purple-900/30">
                                <svg xmlns="http://www.w3.org/2000/svg"
                                    class="w-5 h-5 text-purple-600 dark:text-purple-400" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                                </svg>
                            </div>
                            <div>
                                <p class="font-medium">HVAC System #INV-2019-0456</p>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Scheduled for tomorrow</p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <div class="p-2 mr-3 bg-purple-100 rounded-lg dark:bg-purple-900/30">
                                <svg xmlns="http://www.w3.org/2000/svg"
                                    class="w-5 h-5 text-purple-600 dark:text-purple-400" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                                </svg>
                            </div>
                            <div>
                                <p class="font-medium">Fire Alarm System</p>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Quarterly check on Wednesday</p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <div class="p-2 mr-3 bg-purple-100 rounded-lg dark:bg-purple-900/30">
                                <svg xmlns="http://www.w3.org/2000/svg"
                                    class="w-5 h-5 text-purple-600 dark:text-purple-400" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                                </svg>
                            </div>
                            <div>
                                <p class="font-medium">Backup Generator</p>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Monthly test on Friday</p>
                            </div>
                        </div>
                    </div>
                </div> --}}
            </div>
        </main>
    </div>
</div>
