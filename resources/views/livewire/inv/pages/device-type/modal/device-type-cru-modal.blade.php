<?php

use Livewire\Volt\Component;
use Livewire\Attributes\{On, Locked};
use Paparee\BaleCms\App\Traits\WithGlobalValidationHandler;
use Paparee\BaleInv\App\Models\DeviceType;
use Illuminate\Support\Facades\DB;
use Jantinnerezo\LivewireAlert\LivewireAlert;

new class extends Component {
    use WithGlobalValidationHandler;

    public $edit_mode = false;

    #[Locked]
    public $device_id;

    public $device_type, $device_name, $device_icon;

    #[On('setDeviceTypeData')]
    public function getDeviceTypeData(DeviceType $device_type)
    {
        $this->device_type = $device_type;
        $this->edit_mode = true;
    }

    public function rules()
    {
        return [
            'device_name' => ['required', 'string'],
            'device_icon' => ['required', 'string'],
        ];
    }

    public function store(LivewireAlert $alert)
    {
        // dd($this);
        $this->validate();

        $this->dispatch('disabling-button', params: true);

        DB::beginTransaction();

        try {
            DeviceType::create([
                'device_name' => $this->device_name,
                'device_icon' => $this->device_icon,
            ]);

            session()->flash('saved', [
                'title' => 'Device Type Created!',
            ]);

            $this->redirect('device-types', navigate: true);
        } catch (\Throwable $th) {
            DB::rollBack();
            info($th->getMessage());

            $alert->title('Something wrong!')->position('top-end')->error()->toast()->show();
        }
    }

    public function update(LivewireAlert $alert)
    {
        // dd($this);
        $this->validate();

        $this->dispatch('disabling-button', params: true);

        DB::beginTransaction();

        try {
            $this->device_type->update([
                'device_name' => $this->device_name,
                'device_icon' => $this->device_icon,
            ]);

            session()->flash('saved', [
                'title' => 'Device Type Updated!',
            ]);

            $this->redirect('device-types', navigate: true);
        } catch (\Throwable $th) {
            DB::rollBack();
            info($th->getMessage());

            $alert->title('Something wrong!')->position('top-end')->error()->toast()->show();
        }
    }

    public function resetVal()
    {
        $this->reset();
        $this->resetValidation();
    }
}; ?>

<div>
    <form wire:submit='{{ $edit_mode ? 'update' : 'store' }}' x-data="{
        modalTitle: 'Add Device Type',
        deviceId: '',
        deviceName: '',
        deviceIcon: '',
        editMode: false,
        open: false,
        init() {
            this.resetState();
        },
        resetState() {
            this.modalTitle = 'Add Device Type';
            this.deviceId = '';
            this.deviceName = '';
            this.deviceIcon = '';
            this.editMode = false;
        },
        handleDeviceTypeData(detail) {
            this.modalTitle = detail.modalTitle;
            this.deviceId = detail.deviceTypeData.id;
            this.deviceName = detail.deviceTypeData.device_name;
            this.deviceIcon = detail.deviceTypeData.device_icon;
            this.editMode = detail.editMode;
            console.log(detail.editMode);
        },
    }" x-init="init()"
        @device-type-data.window="handleDeviceTypeData($event.detail)" @modal-reset.window="init()">
        <div class="mb-4">
            <x-bale.input label="Device Name" wire:model='device_name' x-model="deviceName" />
            <x-input-error for="device_name" />

        </div>
        <div class="mb-4">
            <x-bale.input label="Device Icon" wire:model='device_icon' x-model="deviceIcon" />
            <x-input-error for="device_icon" />
        </div>

        <x-bale.modal-action>
            <x-bale.button type="submit" label="{{ $edit_mode ? 'update' : 'store' }}" @click="useSpinner()"
                class="ml-3" />
            <x-bale.secondary-button label="Cancel" type="button"
                wire:click="$dispatch('closeBaleModal', { id: 'deviceTypeModal' }); $wire.resetVal()" />
        </x-bale.modal-action>
    </form>
</div>
