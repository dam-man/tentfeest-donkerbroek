@php use Carbon\Carbon; @endphp
		<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="csrf-token" content="{{ csrf_token() }}">

	<title>{{ config('app.name', 'Tentfeest Donkerbroek') }}</title>

	<!-- Fonts -->
	<link rel="preconnect" href="https://fonts.bunny.net">
	<link href="https://fonts.bunny.net/css?family=inter:400,500,600&display=swap" rel="stylesheet"/>

	<style>
        .ticket-container {
            font-family:    'Changa', sans-serif;
            text-transform: uppercase;
            font-weight:    800;
            font-size:      1.25rem;
            padding-bottom: 0.75rem;
        }

        .ticket-header {
            display: table;
            width:   100%;
        }

        .ticket-header img {
            height:       3rem;
            width:        3rem;
            margin-right: 1.25rem;
        }

        .ticket-title {
            font-size:   1.50rem;
            color:       #db2777;
            margin-left: 1.25rem;
        }

        .ticket-body {
            display:       table;
            width:         100%;
            border:        2px solid #db2777;
            border-radius: 0.75rem;
        }

        .ticket-row {
            display: table-row;
        }

        .ticket-info, .ticket-sidebar {
            display:        table-cell;
            vertical-align: middle;
            padding:        1.5rem;
        }

        .ticket-info {
            width:          70%;
            position:       relative;
            padding-bottom: 20px !important;
        }

        .ticket-details {
            position:   absolute;
            bottom:     2.5rem;
            right:      1.5rem;
            text-align: right;
            color:      white;
            font-size:  2.25rem;
        }

        .ticket-image {
            width:         465px;
            height:        290px;
            border-radius: 15px;
            float:         left;
            margin-bottom: 50px;
        }

        .ticket-sidebar {
            width:          30%;
            text-align:     center;
            vertical-align: middle;
        }

        .ticket-sidebar-content {
            display:    inline-block;
            text-align: center;
        }

        .ticket-description {
            font-family: 'Changa', 'Helvetica', sans-serif;
            font-weight: bold;
            font-size:   10px;
            margin-top:  5px !important;
        }

        .ticket-sidebar img {
            width:         150px;
            border-radius: 15px;
            display:       block;
            margin:        0 auto 10px auto;
        }

        .ticket-sidebar div {
            font-size: 1.25rem;
        }

        .text-blue {
            color: blue;
        }

        .text-pink {
            color: #db2777;
        }
	</style>

</head>

<body class="m-2">

@foreach($tickets as $ticket)

	<div class="ticket-body" style="margin-bottom: 15px;">
		<div class="ticket-row">
			<div class="ticket-info">
				<img src="{{Storage::url('app/public/tickets/'.$ticket->event->image)}}" class="ticket-image"/>
				<div class="ticket-details" style="z-index:100; ">
					<div style="z-index:1000; top:20px; right: 20px; color:white">04/07/2025 <br/>{{$ticket->event->description}}</div>
				</div>
			</div>
			<div class="ticket-sidebar">
				<div class="ticket-sidebar-content">
					{!! DNS2D::getBarcodeHTML($ticket->barcode, 'QRCODE', 6,6,'#0710db') !!}
					<div class="text-pink ticket-description" style="font-size:14px; margin-top:15px!important;">{{$ticket->barcode}}</div>
					<div class="text-blue ticket-description" style="font-size:18px; margin-top:15px!important; text-transform: uppercase">
						{{Carbon::parse($ticket->event->date)->format('d-m-Y')}}
					</div>
					<div class="text-pink ticket-description" style="font-size:17px;">21:00 UUR</div>
					<div class="text-blue ticket-description" style="font-size:14px; margin-top:15px!important">LOCATIE</div>
					<div class="text-pink ticket-description" style="font-size:13px;">FEESTTERREIN DONKERBROEK</div>
				</div>
			</div>
		</div>

	</div>
	<div style="width:100%; text-align: center; margin-top:-10px; margin-bottom:20px; font-family: 'Changa', 'Helvetica', sans-serif; font-size:12px;">
		Deze barcode is eenmalig te gebruiken. Bij binnenkomst wordt deze gescand. Bij misbruik wordt de toegang geweigerd. Het feestterrein van Donkerbroek
		kan je vinden aan de Oude Tramweg te Donkerbroek. Kom je met de auto, dan kun je deze parkeren naast Restaurant 't Witte Huis aan de Geert Wolter
		Smitweg.
	</div>
@endforeach


</body>

</html>