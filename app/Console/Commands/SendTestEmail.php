<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Mail;

class SendTestEmail extends Command
{
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'mail:test';

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
		$result = Mail::raw('This email confirms that everything was set up correctly!', function ($message) {
			$message->to('info@rd-media.org')
			        ->from('info@tentfeestdonkerbroek.nl')
			        ->subject('Testing Laravel + Mailgun');
		});

		dd($result);
	}
}
