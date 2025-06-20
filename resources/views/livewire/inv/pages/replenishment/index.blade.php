<?php

use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;
use function Livewire\Volt\{title, mount};

title('INV | Replenishment');

mount(function () {
    if (session()->has('saved')) {
        LivewireAlert::title(session('saved.title'))->toast()->position('top-end')->success()->show();
    }
});

?>

<div>
    <livewire:inv.pages.replenishment.section.replenishment-form />
</div>
