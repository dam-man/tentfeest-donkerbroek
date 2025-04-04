<?php

namespace App\Service;

use App\Models\EventOrder;
use Barryvdh\DomPDF\Facade\Pdf;
use Exception;
use Illuminate\Support\Facades\Storage;

class PdfService
{
	public function __construct()
	{
		$this->preCheckFolders();
	}

	private function preCheckFolders(): void
	{
		if ( ! Storage::exists(storage_path('app/tickets')))
		{
			Storage::disk('local')->makeDirectory('tickets');
		}
	}

	/**
	 * @throws \Exception
	 */
	public function generate($id): true
	{
		$tickets = EventOrder::query()
		                     ->with('event')
		                     ->where('order_id', $id)
		                     ->get();

		PDF::loadView('tickets', ['tickets' => $tickets])
		   ->setPaper('A4')
		   ->save(storage_path('app/tickets/') . $id . '.pdf');

		if ( ! Storage::disk('tickets')->exists($id . '.pdf'))
		{
			throw new Exception(__('Failed to generate PDF'));
		}

		return true;
	}
}