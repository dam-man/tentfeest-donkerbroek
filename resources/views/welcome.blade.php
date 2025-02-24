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
		<img src="https://raw.githubusercontent.com/dam-man/tickets-tentfeest/main/logo.png"
			 class="h10 w-10 mr-5"
			 alt="Tentfeest Donkerbroek"/>
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

<flux:sidebar stashable sticky class="lg:hidden bg-zinc-50 dark:bg-zinc-900 border-r border-zinc-200 dark:border-zinc-700">
	<flux:sidebar.toggle class="lg:hidden" icon="x-mark"/>

	<flux:brand href="#" logo="https://fluxui.dev/img/demo/logo.png" name="Acme Inc." class="px-2 dark:hidden"/>
	<flux:brand href="#" logo="https://fluxui.dev/img/demo/dark-mode-logo.png" name="Acme Inc." class="px-2 hidden dark:flex"/>

	<flux:navlist variant="outline">
		<flux:navlist.item icon="home" href="#" current>Home</flux:navlist.item>
		<flux:navlist.item icon="inbox" badge="12" href="#">Inbox</flux:navlist.item>
		<flux:navlist.item icon="document-text" href="#">Documents</flux:navlist.item>
		<flux:navlist.item icon="calendar" href="#">Calendar</flux:navlist.item>

		<flux:navlist.group expandable heading="Favorites" class="max-lg:hidden">
			<flux:navlist.item href="#">Marketing site</flux:navlist.item>
			<flux:navlist.item href="#">Android app</flux:navlist.item>
			<flux:navlist.item href="#">Brand guidelines</flux:navlist.item>
		</flux:navlist.group>
	</flux:navlist>

	<flux:spacer/>


</flux:sidebar>

<flux:main container>
	<flux:heading size="xl" level="1">Good afternoon, Olivia</flux:heading>
	<flux:subheading size="lg" class="mb-6">Here's what's new today</flux:subheading>
	<flux:separator variant="subtle"/>
</flux:main>

<flux:main container>
	<flux:heading size="xl" level="1">Good afternoon, Olivia</flux:heading>
	<flux:subheading size="lg" class="mb-6">Here's what's new today</flux:subheading>
	<flux:separator variant="subtle"/>
</flux:main>

@persist('toast')
<flux:toast/>
@endpersist

@fluxScripts
</body>
</html>