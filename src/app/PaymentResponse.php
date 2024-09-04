<?php


namespace PixellWeb\Paybox\app;



class PaymentResponse
{

    protected string $signature;

    protected array $attribute;

    /**
     * @param string $signature
     * @param array $attribute
     * @return null
     */
    public function __construct(string $signature, array $attribute)
    {
        $this->signature = base64_decode($signature);
        $this->attribute = $attribute;
    }

    /**
     * @return bool
     */
    public function verifySignature() :bool
    {
        $string_param = Tools::getSignedData($this->attribute);

        $public_key = openssl_pkey_get_public(config('paybox.public_key'));
        return 1 == openssl_verify($string_param, $this->signature, $public_key);
    }

}
