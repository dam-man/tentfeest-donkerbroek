<?php

use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.admin')] class extends Component {
    //
};

?>

<div>
    <div>
        <flux:heading size="xl">403 - Geen Toegang</flux:heading>
        <flux:subheading>U heeft geen toegang tot deze pagina, vraag de administrator om u toegang te geven.</flux:subheading>
    </div>
</div>
