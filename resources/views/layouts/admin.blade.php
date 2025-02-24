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

<body class="min-h-screen bg-white dark:bg-zinc-800 antialiased">
<flux:sidebar sticky stashable class="bg-zinc-50 dark:bg-zinc-900 border-r border-zinc-200 dark:border-zinc-700">
	<flux:sidebar.toggle class="lg:hidden" icon="x-mark"/>

	<img src="https://raw.githubusercontent.com/dam-man/tickets-tentfeest/main/logo.png" class="h12 w-12"/>

	<livewire:components.administrator.menu/>

	<flux:spacer/>

	<livewire:administrator.auth.logout/>

</flux:sidebar>

<flux:header class="lg:hidden">
	<flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left"/>

	<flux:spacer/>

	<flux:dropdown position="top" alignt="start">
		<flux:profile avatar="https://fluxui.dev/img/demo/user.png"/>

		<flux:menu>
			<flux:menu.radio.group>
				<flux:menu.radio checked>Olivia Martin</flux:menu.radio>
				<flux:menu.radio>Truly Delta</flux:menu.radio>
			</flux:menu.radio.group>

			<flux:menu.separator/>

			<flux:menu.item icon="arrow-right-start-on-rectangle">Logout</flux:menu.item>
		</flux:menu>
	</flux:dropdown>
</flux:header>

<flux:main>
	{{ $slot }}
</flux:main>

@persist('toast')
<flux:toast/>
@endpersist

@fluxScripts
</body>

</html>
