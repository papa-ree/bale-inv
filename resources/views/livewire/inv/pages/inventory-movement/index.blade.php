<?php

use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;
use function Livewire\Volt\{title, mount};

title('INV | Stock Movement');

mount(function () {
    if (session()->has('saved')) {
        LivewireAlert::title(session('saved.title'))->toast()->position('top-end')->success()->show();
    }
});
?>

<div>
    <x-bale.page-header title="Stock Movement" />

    <livewire:inv.pages.inventory-movement.section.inventory-movement-table />
</div>
