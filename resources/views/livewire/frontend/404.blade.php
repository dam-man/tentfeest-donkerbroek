<?php

use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.frontend')] class extends Component {

};

?>

<div>
    <livewire:components.frontend.title
            title="404"
            mobile="404"
            color="#0710db"
            align="left"
    />

    <div class="w-full md:w-1/2">
        <p>
            Oops, deze pagina bestaat niet!
        </p>
    </div>

</div>
