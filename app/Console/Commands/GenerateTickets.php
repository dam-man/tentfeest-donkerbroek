<?php

namespace App\Console\Commands;

use App\Models\EventOrder;
use App\Service\PdfService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Console\Command;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Storage;
use \Milon\Barcode\DNS1D;

class GenerateTickets extends Command
{
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'app:generate-tickets';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Command description';

	/**
	 * Execute the console command.
	 */
	public function handle()
	{
		(new PdfService())->generate(11378);

		dd('JOE');
		$orders = [];

		$tickets = EventOrder::query()
		                     ->with('event')
		                     ->where('order_id', 11378)
		                     ->get();

		foreach ($tickets as $ticket)
		{
			$qrCode = QrCode::size(100)
			                ->color(190, 24, 93)
			                ->generate($ticket->barcode);

			Storage::put('public/codes/' . $ticket->barcode . '.png', $qrCode);
		}

		$pdf = PDF::loadView('tickets', ['tickets' => $tickets]);

		$pdf->setPaper('A4')->save(storage_path('app') . '/test.pdf');
	}

}
