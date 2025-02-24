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

<body class="min-h-screen bg-pink">

<flux:header container class="bg-blue">

	<flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left"/>

	<a href="/">
		<img
				src="https://raw.githubusercontent.com/dam-man/tickets-tentfeest/main/logo.png"
				class="h10 w-10 mr-5"
				alt="Tentfeest Donkerbroek"
		/>
	</a>

	<flux:navbar class="-mb-px max-lg:hidden !h-20 change">
		<flux:navbar.item href="#" current class="!font-family-changa">
			<span class="text-white text-xl hover:text-pink">Home</span>
		</flux:navbar.item>
		<flux:navbar.item href="#" class="font-family-changa">
			<span class="text-white text-xl">Bestellen</span>
		</flux:navbar.item>
		<flux:navbar.item href="#" class="font-family-changa">
			<span class="text-white text-xl">Nieuws</span>
		</flux:navbar.item>
		<flux:navbar.item href="#" class="font-family-changa">
			<span class="text-white text-xl">Inloggen</span>
		</flux:navbar.item>
		<flux:navbar.item href="#" class="font-family-changa">
			<span class="text-white text-xl">Registreren</span>
		</flux:navbar.item>

		{{--		<flux:separator vertical variant="subtle" class="my-2"/>--}}

		{{--		<flux:dropdown class="max-lg:hidden">--}}
		{{--			<flux:navbar.item icon-trailing="chevron-down">Favorites</flux:navbar.item>--}}

		{{--			<flux:navmenu>--}}
		{{--				<flux:navmenu.item href="#">Marketing site</flux:navmenu.item>--}}
		{{--				<flux:navmenu.item href="#">Android app</flux:navmenu.item>--}}
		{{--				<flux:navmenu.item href="#">Brand guidelines</flux:navmenu.item>--}}
		{{--			</flux:navmenu>--}}
		{{--		</flux:dropdown>--}}
	</flux:navbar>

	{{--	<flux:spacer/>--}}

	{{--	<flux:navbar class="mr-4">--}}
	{{--		<flux:navbar.item icon="magnifying-glass" href="#" label="Search"/>--}}
	{{--		<flux:navbar.item class="max-lg:hidden" icon="cog-6-tooth" href="#" label="Settings"/>--}}
	{{--		<flux:navbar.item class="max-lg:hidden" icon="information-circle" href="#" label="Help"/>--}}
	{{--	</flux:navbar>--}}

	{{--	<flux:dropdown position="top" align="start">--}}
	{{--		<flux:profile avatar="https://fluxui.dev/img/demo/user.png"/>--}}

	{{--		<flux:menu>--}}
	{{--			<flux:menu.radio.group>--}}
	{{--				<flux:menu.radio checked>Olivia Martin</flux:menu.radio>--}}
	{{--				<flux:menu.radio>Truly Delta</flux:menu.radio>--}}
	{{--			</flux:menu.radio.group>--}}

	{{--			<flux:menu.separator/>--}}

	{{--			<flux:menu.item icon="arrow-right-start-on-rectangle">Logout</flux:menu.item>--}}
	{{--		</flux:menu>--}}
	{{--	</flux:dropdown>--}}
</flux:header>

<flux:sidebar stashable sticky class="lg:hidden !w-3/4 bg-pink">

	<flux:sidebar.toggle class="lg:hidden" icon="x-mark"/>

	<flux:navlist variant="outline">
		<flux:navlist.item icon="home" href="#" current class="font-family-changa">
			<span class="text-xl">Home</span>
		</flux:navlist.item>
		<flux:navlist.item icon="inbox" badge="12" href="#" class="font-family-changa">
			<span class="text-xl">Bestellingen</span>
		</flux:navlist.item>
		<flux:navlist.item icon="document-text" href="#" class="font-family-changa">
			<span class="text-xl">Test</span>
		</flux:navlist.item>
		<flux:navlist.item icon="calendar" href="#" class="font-family-changa">
			<span class="text-xl">Bestellingen</span>
		</flux:navlist.item>
	</flux:navlist>

</flux:sidebar>

<flux:main container>
	{{$slot}}
</flux:main>

@persist('toast')
<flux:toast/>
@endpersist

@fluxScripts
</body>
</html>