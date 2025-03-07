<?php

namespace App\Http\Controllers;

use App\Models\EventOrder;
use App\Models\Order;
use Auth;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;

class ScanController extends Controller
{
    public function status($barcode, $status): JsonResponse
    {
        try
        {
            $ticket = $this->searchBarcode($barcode);

            $ticket->update([
                'scanned'    => $status,
                'scanned_at' => $status ? now() : null,
                'scanned_by' => $status ? Auth::id() : null,
            ]);

            $ticket->save();
        }
        catch (\Exception $e)
        {
            return response()->json(['message' => $e->getMessage()], 422);
        }

        return response()->json(['message' => 'Status is aangepast.']);
    }

    public function ticket($barcode)
    {
        $ticket = $this->searchBarcode($barcode);

        if (empty($ticket))
        {
            return view('scan.failed', ['barcode' => $barcode, 'result' => 'failed', 'message' => 'Onbekende barcode!']);
        }

        if ($ticket->event_id == 3)
        {
            return view('scan.warning', ['barcode' => $barcode, 'result' => 'failed', 'message' => 'Munten Voucher']);
        }

        if ( ! $this->isPaid($ticket->order_id))
        {
            return view('scan.failed', ['barcode' => $barcode, 'result' => 'failed', 'message' => 'Order Onbetaald']);
        }

        if ($ticket->scanned == 1)
        {
            return view('scan.failed', ['barcode' => $barcode, 'result' => 'failed', 'message' => 'Barcode Reeds Gescand']);
        }

        $this->updateTicket($ticket);

        return view('scan.success', ['barcode' => $barcode, 'message' => 'Veel Plezier!']);
    }

    private function isPaid($orderId)
    {
        $order = Order::where('id', $orderId)->first();

        if ( ! $order)
        {
            return false;
        }

        return $order->status->value === 'paid';
    }

    public function munten($barcode)
    {
        $ticket = $this->searchBarcode($barcode);

        if (empty($ticket))
        {
            return view('scan.failed', ['barcode' => $barcode, 'result' => 'failed', 'message' => 'Onbekende barcode!']);
        }

        if ($ticket->event_id != 3)
        {
            return view('scan.warning', ['barcode' => $barcode, 'result' => 'failed', 'message' => 'Toegangsbewijs']);
        }

        if ( ! $this->isPaid($ticket->order_id))
        {
            return view('scan.failed', ['barcode' => $barcode, 'result' => 'failed', 'message' => 'Order Onbetaald']);
        }

        if ($ticket->scanned == 1)
        {
            return view('scan.failed', ['barcode' => $barcode, 'result' => 'failed', 'message' => 'Barcode reeds gescand!']);
        }

        $this->updateTicket($ticket);

        return view('scan.success', ['barcode' => $barcode, 'result' => $ticket->quantity * 10 . ' Munten', 'message' => 'Veel Plezier']);
    }

    private function updateTicket($ticket): void
    {
        $ticket->scanned    = 1;
        $ticket->scanned_by = Auth::id();
        $ticket->scanned_at = now();
        $ticket->save();
    }

    private function searchBarcode($barcode): Model|Builder|null
    {
        return EventOrder::query()
                         ->where('barcode', $barcode)
                         ->first();
    }
}
