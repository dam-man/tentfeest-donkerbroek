<?php

namespace App\Http\Controllers;

use App\Jobs\GenerateTicketsJob;
use App\Jobs\SendPaymentNotificationJob;
use App\Jobs\SendTicketsJob;
use App\Models\Coupon;
use App\Models\Event;
use App\Models\EventOrder;
use App\Models\Order;
use App\Models\Payment;
use App\Models\User;
use App\Notifications\SendTicketsNotification;
use App\Service\MolliePaymentService;
use App\Services\MollieService;
use App\Services\PdfService;
use App\Traits\HasOrder;
use App\Traits\HasShoppingCartSession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Mollie\Api\Exceptions\ApiException;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class PaymentController extends Controller
{
	use HasShoppingCartSession, HasOrder;

	/**
	 * @throws ApiException
	 */
	public function webhook(Request $request)
	{
		if ( ! $request->has('id'))
		{
			die();
		}

		// Get the status from mollie
		$transaction = (new MolliePaymentService)->getTransaction($request->id);

		// Getting the database stuff.
		$payment = Payment::where('payment_id', $request->id)->first();
		$order   = Order::where('payment_id', $request->id)->first();

		// Checking information from tables.
		if (empty($payment) || empty($order))
			die('Failure');

		exit('JO');
		// Update payment
		$payment->status   = $transaction->status;
		$order->updated_at = now();
		$payment->save();

		// Updating the order.
		$order->status     = $transaction->status;
		$order->updated_at = now();
		$order->save();

		// Checking if the payment is paid.
		$toBePaid = number_format($payment->amount / 100, 2, '', '');
		$isPaid   = number_format($transaction->amount->value, 2, '', '');

		if ($transaction->status === 'paid' && $toBePaid == $isPaid)
		{
			//SendTicketsJob::dispatch($order->id);

			// Update tickets sales
			$this->updateEventSoldTotals($order->id);

			if ($order->coupon_id)
			{
				Coupon::whereId($order->coupon_id)->increment('usage');
			}
		}

		return true;
	}

	public function updateEventSoldTotals($orderId): void
	{
		$tickets = EventOrder::whereOrderId($orderId)->get();

		foreach ($tickets as $ticket)
		{
			$event = Event::whereId($ticket->event_id)->first();

			$event->sold = $event->sold + $ticket->quantity;
			$event->save();
		}
	}

	public function completed($token)
	{
		$this->clearCart();

		$payment = Payment::query()
		                  ->with('order')
		                  ->whereRelation('order', 'user_id', Auth::id())
		                  ->where('token', $token)
		                  ->first();

		dd($payment);
		if ( ! $payment)
		{
			return view('payment.failed');
		}

		$whatsapp = 'https://wa.me/31621666730?text=' . urlencode('Ik heb een vraag over de betaling met kenmerk ' . $payment->order_id);

		return view('payment.completed', compact('payment', 'whatsapp'));
	}
}
