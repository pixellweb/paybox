<?php


namespace PixellWeb\Paybox\app;



class PaymentResponse
{

    protected string $signature;

    protected array $attribute;

    /**
     * @param string $signature
     * @param array $attribute
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
        if (1 == openssl_verify($string_param, $this->signature, $public_key)) {
            return true;
        }
        // TODO a supprimer dans quelques mois (a cause de paiements en plusieurs fois sur lokizy)
        $public_key = openssl_pkey_get_public(config('paybox.public_key_old'));
        return 1 == openssl_verify($string_param, $this->signature, $public_key);
    }

}
