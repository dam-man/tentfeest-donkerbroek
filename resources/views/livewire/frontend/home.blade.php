<?php

use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.frontend')] class extends Component {
    //
};

?>

<div>
    <div class="w-screen h-screen bg-pink">
        <flux:main container>
            <livewire:components.frontend.header/>
        </flux:main>
    </div>

    <div class="bg-blue">
        <flux:main container>

            <livewire:components.frontend.program/>
            <livewire:components.frontend.news/>
            <livewire:components.frontend.footer/>

        </flux:main>
    </div>
</div>
