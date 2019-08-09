<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class IPNHandlerCoinPaymentJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $trx;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($trx) {
        $this->trx = $trx;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle() {
		if($this->trx['status'] === 1 || $this->trx['status'] === 100){
			$payload = $this->trx['payload'];
			$deposit = Deposit::find($payload['deposit_id']);
			$deposit->status = 1;
			$deposit->balance->balance += $deposit->amount;
			$deposit->save();
		}
		if($this->trx['status'] === -1 ){
			$payload = $this->trx['payload'];
			$deposit = Deposit::find($payload['deposit_id']);
			$deposit->status = 2;
			$deposit->save();
		}
        // Do something...

        /* DATA RESPONSE
          $this->trx['payment_id'];
          $this->trx['payment_address'];
          $this->trx['coin'];
          $this->trx['fiat'];
          $this->trx['status_text'];
          $this->trx['status'];
          $this->trx['payment_created_at'];
          $this->trx['confirmation_at'];
          $this->trx['amount'];
          $this->trx['confirms_needed'];
          $this->trx['payload']; // Your custom data: Array()
        */
		

    }
}
