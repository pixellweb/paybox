<?php


namespace PixellWeb\Paybox\app;


use Throwable;

class PayboxException extends \Exception
{
    /**
     * ReferentielApiException constructor.
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct(string $message = "", int $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
        \Log::channel(config('paybox.logging_channel'))->alert($message);
    }
}
