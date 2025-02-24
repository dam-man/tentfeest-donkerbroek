<?php

use Livewire\Volt\Component;

new class extends Component {

	public string $title;
	public string $mobile;
	public string $align = 'center';
	public string $color = '#e6007e';

};

?>

<div>
	<div class="sm:pt-4 md:pt-6 mb-8">
		<h2 class="{{$align === 'center' ? 'text-center' : ''}} text-white  font-bold font-family-changa uppercase">
			<span class="hidden md:block text-4xl">{{$title}}</span>
			<span class="block md:hidden text-3xl">{{$mobile}}</span>
        </h2>
        <div class="flex {{$align === 'center' ? 'items-center justify-center' : ''}}">
            <div class="text-white border border-r-0 border-l-0 border-t-0 border-b-4 mt-2 w-52" style="border-color: {{$color}}"/>
        </div>
    </div>
</div>
