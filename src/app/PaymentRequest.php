<?php


namespace PixellWeb\Paybox\app;



class PaymentRequest
{

    const PBX_RETOUR = [
        'M' => 'montant',
        'R' => 'reference',
        'S' => 'transaction_ref',
        'A' => 'autorisation_ref',
        'E' => 'erreur',
        'K' => 'signature'
    ];
    const PBX_RETOUR_SIGNATURE = 'signature';


    public function __construct(string $reference, float $montant, string $mail)
    {

    }


    public function link()
    {
        $query = '';
        foreach ($this->getFormFields() as $key => $value) {
            $query .= "&" . $key . '=' . urlencode($value);
        }
        $query = ltrim($query, '&');

        return config('paybox.payment_page_url') . '?' . $query;
    }

    /**
     * Find a suitable hashing algorithm
     * @return string the algorithm
     * @throws PayboxException
     * @throw \RuntimeException if no algorithm was found.
     */
    protected function getHashAlgorithm()
    {
        // Possible hashes
        $hashes = array(
            'sha512',
            'sha256',
            'sha384',
            'ripemd160',
            'sha224',
            'mdc2'
        );
        $hashEnabled = hash_algos();
        foreach ($hashes as $hash) {
            if (in_array($hash, $hashEnabled)) {
                return strtoupper($hash);
            }
        }
        throw new PayboxException("Failed to find a suitable hash algorithm. Please check your PHP configuration.");
    }

    static public function getPbxRetourSring() :string
    {
        $pbx_retour = [];

        foreach (self::PBX_RETOUR as $code => $variable) {
            $pbx_retour[] = $variable.':'.$code;
        }
        return implode(';', $pbx_retour);
    }

}
