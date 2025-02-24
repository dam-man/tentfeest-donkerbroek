<?php

use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.admin')] class extends Component {
	//
};

?>

<div>
    <div>
        <flux:heading size="xl">404 - Onbekende Pagina</flux:heading>
        <flux:subheading>De opgevraagde pagina bestaat niet, prbeer een andere pagina.</flux:subheading>
    </div>
</div>
