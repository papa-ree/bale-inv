<?php

use Paparee\BaleInv\App\Models\InventoryAssignment;
use Paparee\BaleInv\App\Models\InventoryItem;
use Livewire\WithoutUrlPagination;
use function Livewire\Volt\{computed, usesPagination, state, uses, updating, hydrate, on};

uses([WithoutUrlPagination::class]);

usesPagination();

state(['query']);

updating([
    'query' => fn() => $this->resetPage(),
]);

hydrate(fn() => $this->dispatch('paginated'));

$availableItems = computed(function () {
    $searchTerm = htmlspecialchars($this->query, ENT_QUOTES, 'UTF-8');

    return InventoryAssignment::query()
        ->whereHas('movement.inventory', function ($q) use ($searchTerm) {
            $q->where('inventoryable_type', InventoryItem::class)->whereHasMorph('inventoryable', [InventoryItem::class], function ($q2) use ($searchTerm) {
                $q2->where('inventory_item_name', 'like', '%' . $searchTerm . '%');
            });
        })
        ->with('movement.inventory.inventoryable')
        ->orderByDesc('created_at')
        ->paginate(20);
});

on([
    'refresh-inventory-item-list' => function () {
        return $this->availableItems;
    },
]);

?>

<div>
    <x-bale.table :links="$this->availableItems" header>

        <x-slot name="thead">
            <tr>
                <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900">
                    <div class="flex items-center gap-x-2">
                        <span class="text-xs font-semibold tracking-wide text-gray-800 uppercase dark:text-gray-200">
                            {{ __('Item Name') }}
                        </span>
                    </div>
                </th>
                <th scope="col" class="hidden px-3 py-3.5 text-left text-sm font-semibold text-gray-900 lg:table-cell">
                    <div class="flex items-center gap-x-2">
                        <span class="text-xs font-semibold tracking-wide text-gray-800 uppercase dark:text-gray-200">
                            {{ __('Assigned To') }}
                        </span>
                    </div>
                </th>
                <th scope="col"
                    class="hidden px-3 py-3.5 text-left text-sm font-semibold text-gray-900 lg:table-cell">
                    <div class="flex items-center gap-x-2">
                        <span class="text-xs font-semibold tracking-wide text-gray-800 uppercase dark:text-gray-200">
                            {{ __('Location') }}
                        </span>
                    </div>
                </th>
                <th scope="col"
                    class="hidden px-3 py-3.5 text-left text-sm font-semibold text-gray-900 md:table-cell">
                    <div class="flex items-center gap-x-2">
                        <span class="text-xs font-semibold tracking-wide text-gray-800 uppercase dark:text-gray-200">
                            {{ __('Assigned Date') }}
                        </span>
                    </div>
                </th>
                <th scope="col"
                    class="hidden px-3 py-3.5 text-left text-sm font-semibold text-gray-900 md:table-cell">
                    <div class="flex items-center gap-x-2">
                        <span class="text-xs font-semibold tracking-wide text-gray-800 uppercase dark:text-gray-200">
                            {{ __('Return Date') }}
                        </span>
                    </div>
                </th>
                <th scope="col"
                    class="hidden px-3 py-3.5 text-left text-sm font-semibold text-gray-900 sm:table-cell">
                    <div class="flex items-center gap-x-2">
                        <span class="text-xs font-semibold tracking-wide text-gray-800 uppercase dark:text-gray-200">
                            {{ __('Status') }}
                        </span>
                    </div>
                </th>
                <th scope="col" class="relative py-3.5 pl-3 pr-4">
                    <span class="sr-only">Edit</span>
                </th>
            </tr>
        </x-slot>

        <x-slot name="tbody">
            @foreach ($this->availableItems as $item)
                <tr wire:key='record-{{ $item->id }}' class="hover:bg-gray-50 dark:hover:bg-slate-700/50">
                    <td class="w-full py-4 pl-4 pr-3 text-sm font-medium text-gray-900 max-w-0 sm:w-auto sm:max-w-none">
                        <div class="flex items-center text-sm text-gray-800 dark:text-gray-200 ">
                            <div class="inline-block hs-tooltip [--placement:right]">
                                {{ $item->item_name }}
                            </div>
                        </div>
                        <dl class="font-normal lg:hidden">
                            <dt class="sr-only">Contact Location</dt>
                            <dd class="mt-1 text-gray-700 truncate">
                                <span class="block text-xs text-gray-500 dark:text-gray-200">
                                    {{ $item->assign_contact }}
                                </span>
                            </dd>
                            <dt class="sr-only">Assigned To</dt>
                            <dd class="mt-1 text-gray-700 truncate">
                                <span class="block text-xs text-gray-500 dark:text-gray-200">
                                    {{ $item->contact_location }}
                                </span>
                            </dd>
                            <dt class="sr-only md:hidden">Assigned At</dt>
                            <dd class="mt-1 text-gray-500 truncate sm:hidden">
                                <span class="block text-xs text-gray-500">Created At
                                    {{ $item->assigned_at }}</span>
                            </dd>
                            <dt class="sr-only md:hidden">Return At</dt>
                            <dd class="mt-1 text-gray-500 truncate sm:hidden">
                                <span class="block text-xs text-gray-500">Created At
                                    {{ $item->returned_at }}</span>
                            </dd>
                            <dt class="sr-only sm:hidden">Created At</dt>
                            <dd class="mt-1 text-gray-500 truncate sm:hidden">
                                <span class="block text-xs text-gray-500">Created At
                                    {{ $item->status }}</span>
                            </dd>
                        </dl>
                    </td>

                    <td class="hidden px-3 py-4 text-sm text-gray-500 lg:table-cell">
                        {{ $item->assign_contact }}
                    </td>

                    <td class="hidden px-3 py-4 text-sm text-gray-500 lg:table-cell">
                        {{ $item->contact_location }}
                    </td>

                    <td class="hidden px-3 py-4 text-sm text-gray-500 md:table-cell">
                        {{ $item->assigned_at }}
                    </td>

                    <td class="hidden px-3 py-4 text-sm text-gray-500 md:table-cell">
                        {{ $item->returned_at ?? '-' }}
                    </td>

                    <td class="hidden px-3 py-4 text-sm text-gray-500 sm:table-cell">
                        {{ $item->status }}
                    </td>

                    <td class="py-4 pl-3 pr-4 text-sm font-medium text-right ">
                        <div class="hs-dropdown relative inline-block [--placement:bottom|left]">
                            <button id="hs-table-dropdown-{{ $item->id }}" type="button"
                                class="hs-dropdown-toggle py-1.5 px-2 inline-flex justify-center items-center gap-2 rounded-lg text-gray-700 align-middle disabled:opacity-50 disabled:pointer-events-none focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-white focus:ring-emerald-300 transition-all text-sm dark:text-neutral-400 dark:hover:text-white dark:focus:ring-offset-gray-800">
                                <svg class="flex-shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24"
                                    height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="12" cy="12" r="1" />
                                    <circle cx="19" cy="12" r="1" />
                                    <circle cx="5" cy="12" r="1" />
                                </svg>
                            </button>

                            <div class="hs-dropdown-menu transition-[opacity,margin] duration border border-gray-200 hs-dropdown-open:opacity-100 opacity-0 hidden divide-y divide-gray-200 min-w-40 z-10 bg-white shadow-2xl rounded-lg p-2 mt-2 dark:divide-neutral-700 dark:bg-neutral-800 dark:border dark:border-neutral-700"
                                aria-labelledby="hs-table-dropdown-{{ $item->id }}">
                                <div class="py-2 first:pt-0 last:pb-0">

                                    <a href="{{ route('returns.index', $item->id) }}" wire:navigate.hover
                                        class="flex items-center w-full px-3 py-2 text-sm text-gray-800 rounded-lg gap-x-3 hover:bg-gray-100 focus:ring-2 focus:ring-emerald-500 dark:text-neutral-400 dark:hover:bg-neutral-700 dark:hover:text-neutral-300">
                                        Return Item
                                    </a>

                                </div>
                            </div>

                        </div>
                    </td>
                </tr>
            @endforeach
        </x-slot>
    </x-bale.table>

</div>
