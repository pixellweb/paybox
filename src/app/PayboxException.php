<?php


namespace PixellWeb\Paybox\app;


class PayboxException extends \Exception
{
    /**
     * ReferentielApiException constructor.
     * @param string $message
     * @param int $code
     * @param \Throwable|null $previous
     */
    public function __construct($message = "", $code = 0, \Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
        \Log::channel(config('paybox.logging_channel'))->alert($message);
    }
}
