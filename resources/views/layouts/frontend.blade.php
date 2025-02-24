<!DOCTYPE html>
<html data-theme="light" lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="csrf-token" content="{{ csrf_token() }}">

	<title>{{ config('app.name', 'Tentfeest Donkerbroek') }}</title>

	<!-- Fonts -->
	<link rel="preconnect" href="https://fonts.bunny.net">
	<link href="https://fonts.bunny.net/css?family=inter:400,500,600&display=swap" rel="stylesheet"/>

	<!-- Styles / Scripts -->
	@if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
		@vite(['resources/css/app.css', 'resources/js/app.js'])
	@endif

	@fluxStyles
</head>

<body class="min-h-screen bg-pink" style="overflow-x: hidden; overflow-y: scroll;">

<flux:header container sticky class="bg-blue">

	<flux:sidebar.toggle class="lg:hidden mt-2 mr-1 lg:mt-0" icon="bars-2" inset="left"/>

	<a href="/">
		<img
				src="https://raw.githubusercontent.com/dam-man/tickets-tentfeest/main/logo.png"
				class="h10 w-10 mr-5 mt-2 lg:mt-0"
				alt="Tentfeest Donkerbroek"
		/>
	</a>

	<livewire:components.frontend.menu-navbar/>

	<flux:spacer />

	<flux:button wire:navigate="{{route('order')}}" variant="primary" size="sm"
				 class="!bg-pink font-family-changa mt-2 md:mt-0 z-50">
		Ik wil kaarten Bestellen!
	</flux:button>

</flux:header>

<flux:sidebar stashable sticky class="lg:hidden !w-3/4 bg-pink">

	<flux:sidebar.toggle class="lg:hidden" icon="x-mark"/>

	<livewire:components.frontend.menu-sidebar/>

</flux:sidebar>

@if (Route::currentRouteName() === 'home')
	{{$slot}}
@else
	<flux:main container>
		{{$slot}}
	</flux:main>
@endif

@persist('toast')
<flux:toast/>
@endpersist

@fluxScripts

</body>
</html>