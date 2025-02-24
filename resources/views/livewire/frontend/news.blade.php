<?php

use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.frontend')] class extends Component {
    //
};

?>

<div>
    <livewire:components.frontend.title
            title="Laatste Nieuws"
            mobile="Laatste Nieuws"
            color="#0710db"
            align="left"
    />

    <div class="flex flex-wrap mt-1 mb-10 md:mb-0 md:mt-8">
        <div class="w-full md:w-1/4 p-1">
            LEFT
        </div>
        <div class="w-full md:w-3/4 p-1">
            RIGHT
        </div>
    </div>
</div>
