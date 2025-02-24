<?php

use Livewire\Volt\Component;

new class extends Component {

    public function logout()
    {
        Auth::guard('web')->logout();

        Session::invalidate();
        Session::regenerateToken();

        $this->redirect(route('administrator.auth.login'), navigate: true);
    }
};

?>

<div>
    <flux:navlist variant="outline">
        <flux:navlist.item wire:click="logout" icon="arrow-right-start-on-rectangle" class="!text-red-400">
            <span class="!text-red-400">Uitloggen {{auth()->user()->name}}</span>
        </flux:navlist.item>
    </flux:navlist>
</div>
