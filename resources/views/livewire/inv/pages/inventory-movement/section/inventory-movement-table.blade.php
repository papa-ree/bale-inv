<?php

use Paparee\BaleInv\App\Models\InventoryMovement;
use Paparee\BaleInv\App\Models\InventoryItem;
use Livewire\WithoutUrlPagination;
use Illuminate\Database\Eloquent\Builder;
use function Livewire\Volt\{computed, usesPagination, state, uses, updating, hydrate, on};

uses([WithoutUrlPagination::class]);

usesPagination();

state(['query']);

updating([
    'query' => fn() => $this->resetPage(),
]);

hydrate(fn() => $this->dispatch('paginated'));

$availableMoving = computed(function () {
    $searchTerm = htmlspecialchars($this->query, ENT_QUOTES, 'UTF-8');

    return InventoryMovement::query()
        ->whereHas('inventory', function ($q) use ($searchTerm) {
            $q->where('item_name', 'like', '%' . $searchTerm . '%');
        })
        ->with('inventory')
        ->orderByDesc('created_at')
        ->paginate(20);
});

on([
    'refresh-inventory-list' => function () {
        return $this->availableMoving;
    },
]);

?>

<div>
    <x-bale.table :links="$this->availableMoving" header>

        <x-slot name="thead">
            <tr>
                <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900">
                    <div class="flex items-center gap-x-2">
                        <span class="text-xs font-semibold tracking-wide text-gray-800 uppercase dark:text-gray-200">
                            {{ __('Date') }}
                        </span>
                    </div>
                </th>
                <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900">
                    <div class="flex items-center gap-x-2">
                        <span class="text-xs font-semibold tracking-wide text-gray-800 uppercase dark:text-gray-200">
                            {{ __('Item Name') }}
                        </span>
                    </div>
                </th>
                <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900">
                    <div class="flex items-center gap-x-2">
                        <span class="text-xs font-semibold tracking-wide text-gray-800 uppercase dark:text-gray-200">
                            {{ __('Type') }}
                        </span>
                    </div>
                </th>
                <th scope="col"
                    class="hidden px-3 py-3.5 text-left text-sm font-semibold text-gray-900 lg:table-cell">
                    <div class="flex items-center gap-x-2">
                        <span class="text-xs font-semibold tracking-wide text-gray-800 uppercase dark:text-gray-200">
                            {{ __('In/Out') }}
                        </span>
                    </div>
                </th>
                <th scope="col"
                    class="hidden px-3 py-3.5 text-left text-sm font-semibold text-gray-900 sm:table-cell">
                    <div class="flex items-center gap-x-2">
                        <span class="text-xs font-semibold tracking-wide text-gray-800 uppercase dark:text-gray-200">
                            {{ __('quantity') }}
                        </span>
                    </div>
                </th>
                <th scope="col"
                    class="hidden px-3 py-3.5 text-left text-sm font-semibold text-gray-900 sm:table-cell">
                    <div class="flex items-center gap-x-2">
                        <span class="text-xs font-semibold tracking-wide text-gray-800 uppercase dark:text-gray-200">
                            {{ __('Moved By') }}
                        </span>
                    </div>
                </th>
                <th scope="col" class=" px-3 py-3.5 text-left text-sm font-semibold text-gray-900">
                    <div class="flex items-center gap-x-2">
                        <span class="text-xs font-semibold tracking-wide text-gray-800 uppercase dark:text-gray-200">
                            {{ __('Notes') }}
                        </span>
                    </div>
                </th>
                {{-- <th scope="col" class="relative py-3.5 pl-3 pr-4">
                    <span class="sr-only">Edit</span>
                </th> --}}
            </tr>
        </x-slot>

        <x-slot name="tbody">
            @foreach ($this->availableMoving as $item)
                <tr wire:key='record-{{ $item->id }}' class="hover:bg-gray-50 dark:hover:bg-slate-700/50">
                    <td class="w-full py-4 pl-4 pr-3 text-sm font-medium text-gray-900 max-w-0 sm:w-auto sm:max-w-none">
                        <div class="flex items-center text-sm text-gray-800 dark:text-gray-200 ">
                            <div class="inline-block hs-tooltip [--placement:right]">
                                {{ date_format($item->created_at, 'd-M-Y') }}
                            </div>


                        </div>
                        <dl class="font-normal lg:hidden">
                            <dt class="sr-only">Item Name</dt>
                            <dd class="mt-1 text-gray-700 truncate">
                                <span class="block text-xs text-gray-500 dark:text-gray-200">
                                    {{ $item->inventory->item_name }}
                                </span>
                            </dd>
                            <dt class="sr-only">Item Name</dt>
                            <dd class="mt-1 text-gray-700 truncate">
                                <span class="block text-xs text-gray-500 dark:text-gray-200">
                                    {{ $item->type }}
                                </span>
                            </dd>
                            <dt class="sr-only sm:hidden">Direction</dt>
                            <dd class="mt-1 text-gray-500 truncate sm:hidden">
                                <span class="block text-xs text-gray-500">
                                    {{ $item->direction }}
                                </span>
                            </dd>
                            <dt class="sr-only sm:hidden">Quantity</dt>
                            <dd class="mt-1 text-gray-500 truncate sm:hidden">
                                <span class="block text-xs text-gray-500">
                                    {{ $item->quantity }}
                                </span>
                            </dd>
                        </dl>
                    </td>

                    <td class="hidden px-3 py-4 text-sm text-gray-500 lg:table-cell">
                        {{ $item->inventory->item_name }}
                    </td>

                    <td class="hidden px-3 py-4 text-sm text-gray-500 lg:table-cell">
                        {{ $item->type }}
                    </td>

                    <td class="hidden px-3 py-4 text-sm text-gray-500 md:table-cell">
                        @if ($item->direction == 'in')
                            <span
                                class="px-3 font-semibold py-0.5 rounded-full text-sm text-green-400 bg-green-100">{{ $item->direction }}</span>
                        @else
                            <span
                                class="px-3 font-semibold py-0.5 rounded-full text-sm text-red-400 bg-red-100">{{ $item->direction }}</span>
                        @endif
                    </td>

                    <td class="hidden px-3 py-4 text-sm text-gray-500 md:table-cell">
                        <span class="block text-sm text-gray-500">{{ $item->quantity }}</span>
                    </td>

                    <td class="hidden px-3 py-4 text-sm text-gray-500 sm:table-cell">
                        <span class="block text-sm text-gray-500">{{ $item->user->name ?? '-' }}</span>
                    </td>

                    <td class="px-3 py-4 text-sm text-gray-500">
                        <span class="block text-sm text-gray-500">{{ $item->note }}</span>
                    </td>

                    {{-- <td class="py-4 pl-3 pr-4 text-sm font-medium text-right "> --}}
                    {{-- <div class="hs-dropdown relative inline-block [--placement:bottom|left]">
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

                                    <button
                                        class="flex items-center w-full px-3 py-2 text-sm text-gray-800 rounded-lg gap-x-3 hover:bg-gray-100 focus:ring-2 focus:ring-emerald-500 dark:text-neutral-400 dark:hover:bg-neutral-700 dark:hover:text-neutral-300">
                                        Edit Item
                                    </button>

                                </div>
                            </div>
                        </div> --}}
                    {{-- </td> --}}
                </tr>
            @endforeach
        </x-slot>
    </x-bale.table>
</div>
