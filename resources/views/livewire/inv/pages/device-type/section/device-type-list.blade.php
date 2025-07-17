<?php

use Livewire\Volt\Component;
use function Livewire\Volt\{computed, mount, usesPagination, state, uses, updating, hydrate, on};
use Paparee\BaleInv\App\Models\DeviceType;

$availableDeviceTypes = computed(function () {
    return DeviceType::orderBy('device_name')->get();
});

on([
    'refresh-device-type-list' => function () {
        return $this->availableDeviceTypes;
    },
]);

?>

<div>
    <div>
        {{-- <x-bale.button label="refresh" wire:click='$refresh' />
        <x-lucide-activity /> --}}
        <ul role="list" class="grid grid-cols-1 gap-5 mt-3 sm:grid-cols-2 sm:gap-6 lg:grid-cols-4">

            {{-- add device button --}}
            <li class="flex justify-center col-span-1 transition-all bg-white border-2 rounded-md shadow-xs cursor-pointer dark:bg-gray-800 hover:bg-gray-200"
                @click="$dispatch('openBaleModal', { id: 'deviceTypeModal' }); $dispatch('modal-reset')">
                <div class="flex items-center justify-center text-center">Add device</div>
            </li>
            @foreach ($this->availableDeviceTypes as $device_type)
                <li wire:key='{{ $device_type->id }}' class="flex col-span-1 rounded-md shadow-xs" x-data="{
                    openDeviceTypeModal() {
                            $wire.dispatch('openBaleModal', { id: 'deviceTypeModal' });
                            this.$dispatch('setDeviceTypeData', { device_type: @js($device_type->id) });
                            this.$dispatch('device-type-data', {
                                modalTitle: 'Edit Device Type',
                                deviceTypeData: @js($device_type),
                                editMode: true
                            });
                        },
                        deviceIcon: '{{ $device_type->device_icon }}',
                }">
                    <div
                        class="flex items-center justify-center w-16 text-sm font-medium text-white bg-gray-400 shrink-0 rounded-l-md">
                        <i :data-lucide="deviceIcon" class="" wire:ignore></i>
                    </div>
                    <div
                        class="flex items-center justify-between flex-1 truncate bg-white border-t border-b border-r border-gray-200 rounded-r-md">
                        <div class="flex-1 px-4 py-2 text-sm truncate">
                            <div @click="openDeviceTypeModal" class="font-medium text-gray-900 hover:text-gray-600">
                                {{ $device_type->device_name }}</div>
                            <p class="text-gray-500">{{ $device_type->device_icon }}</p>
                        </div>
                        <div class="pr-2 shrink-0" x-data="{
                            openDeviceTypeDeleteModal() {
                                $wire.dispatch('openBaleModal', { id: 'deviceTypeDeleteModal' });
                                this.$dispatch('device-data', {
                                    deviceTypeId: @js($device_type->id),
                                    deviceTypeName: '{{ $device_type->device_name }}',
                                });
                            }
                        }">
                            <button type ="button" @click="openDeviceTypeDeleteModal"
                                class="inline-flex items-center justify-center text-gray-400 bg-transparent bg-white rounded-full size-8 hover:text-gray-500 focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 focus:outline-hidden">
                                <span class="sr-only">Open options</span>
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round"
                                    class="lucide lucide-trash-icon lucide-trash">
                                    <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6" />
                                    <path d="M3 6h18" />
                                    <path d="M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </li>
            @endforeach
        </ul>
    </div>

    {{-- device type modal --}}
    <x-bale.modal modalId="deviceTypeModal" size="lg" staticBackdrop>
        <livewire:inv.pages.device-type.modal.device-type-cru-modal />
    </x-bale.modal>

    {{-- device type modal --}}
    <x-bale.modal modalId="deviceTypeDeleteModal" size="lg" staticBackdrop>
        <livewire:inv.pages.device-type.modal.device-type-delete-confirmation-modal />
    </x-bale.modal>
</div>
