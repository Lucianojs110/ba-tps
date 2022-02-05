<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class InfoEtiquecosas extends Mailable
{
    use Queueable, SerializesModels;

    public $subjet = "Informacion de su pedido";

    /**
     * Create a new message instance.
     *
     * @return void
     */

    public $mensaje;


    public function __construct($mensaje)
    {
        $this->mensaje = $mensaje;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
       
        return $this->from('lucianojs110yyy@gmail.com')
                    ->replyTo('lucianojs110@gmail.com')
                    ->subject('Información de su pedido')
                    ->view('mail.mailestados')
                    ->with(["mensaje" => $this->mensaje]);
    }
}
