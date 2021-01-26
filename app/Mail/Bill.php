<?php
/**
 * Updated by Leestarch at 9/2/20 1:02 PM
 */

namespace App\Mail;

use App\Models\Option;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class Bill extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Bill $bill, $text)
    {
        $this->bill = $bill;
        $this->text = $text;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {

        return $this
            ->subject('Pino')
            ->markdown('emails.bills.index', ['bill' => $this->bill, 'text' => $this->text]);
    }
}
