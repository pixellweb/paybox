<?php


namespace PixellWeb\Paybox\app;




class PaymentRequest
{

    protected ?array $echeancier;

    protected int $total_quantite = 1;

    const PBX_RETOUR = [
        'M' => 'montant',
        'R' => 'reference',
        'S' => 'transaction_ref',
        'A' => 'autorisation_ref',
        'E' => 'erreur',
        // signature should be always last return field
        'K' => 'signature'
    ];
    const PBX_RETOUR_SIGNATURE = 'signature';



    /**
     * @param array|null $echeancier
     */
    public function setEcheancier(?array $echeancier): void
    {
        $this->echeancier = $echeancier;
    }




    /**
     * Payment gateway invocation
     *
     * @param Reservation $reservation
     * @return array
     * @throws PayboxException
     * @internal param Order $order processed order
     */
    public function pay(): array
    {
        $hash_algo = $this->getHashAlgorithm();

        // TODO $amount = $reservation->echeancier->count() ?  $reservation->echeancier->first()->montant : ($reservation->acompte ?? $reservation->total);

        $paybox_params = array(
            'PBX_SITE'         => config('paybox.site'),
            'PBX_RANG'         => config('paybox.rang'),
            'PBX_IDENTIFIANT'  => config('paybox.identifiant'),
            'PBX_RETOUR'       => self::getPbxRetourSring(),
            'PBX_HASH'         => $hash_algo,
            'PBX_ANNULE'       => route(config('paybox.url_refuse')),
            'PBX_EFFECTUE'     => route(config('paybox.url_effectue')),
            'PBX_ATTENTE'      => route(config('paybox.url_attente')),
            'PBX_REFUSE'       => route(config('paybox.url_refuse')),
            'PBX_REPONDRE_A'   => route(config('paybox.url_repondre_a')),
            'PBX_TOTAL'        => $this->formatMontant($this->montant),
            'PBX_DEVISE'       => config('paybox.devise'),
            'PBX_CMD'          => $this->reference,
            'PBX_PORTEUR'      => $this->email,
            'PBX_TIME'         => date("c"),
            'PBX_RUF1'         => 'POST',
            //'PBX_TYPEPAIEMENT' => 'CARTE',
            //'PBX_TYPECARTE'    => 'CB',
            //'PBX_LANGUE'       =>  App::getLocale() == 'us' ? 'GBR' : 'FRA',
            'PBX_SHOPPINGCART'    => '<?xml version="1.0" encoding="utf-8"?><shoppingcart><total><totalQuantity>'.$this->formatTextValue($this->total_quantite, 'N', 2).'</totalQuantity></total></shoppingcart>',
            'PBX_BILLING'       => '<?xml version="1.0" encoding="utf-8"?><Billing><Address><FirstName>'.$this->formatTextValue($this->prenom, 'ANS', 30).'</FirstName><LastName>'.$this->formatTextValue($this->nom, 'ANS', 30).'</LastName><Address1>'.$this->formatTextValue($this->adresse, 'ANS', 50).'</Address1><ZipCode>'.$this->formatTextValue($this->cp, 'ANS', 16).'</ZipCode><City>'.$this->formatTextValue($this->ville, 'ANS', 50).'</City><CountryCode>'.$this->formatTextValue($this->pays_code, 'N', 3).'</CountryCode></Address></Billing>',
        );

        //paiement en plusieurs fois
        if ($this->echeancier) {
            foreach ($this->echeancier as $key => $echeance) {
                $paybox_params += [
                    'PBX_2MONT'.($key + 1) => $this->formatMontant($echeance['montant']),
                    'PBX_DATE'.($key + 1) => $echeance['date'],
                ];
            }
        }

        // Generate signature
        $param = '';
        foreach ($paybox_params as $key => $value) {
            $param .= "&" . $key . '=' . $value;
        }
        $param = ltrim($param, '&');

        $binkey = pack('H*', config('paybox.secret'));
        $paybox_params['PBX_HMAC'] = strtoupper(hash_hmac($hash_algo, $param, $binkey));

        return $paybox_params;
    }

    public function link()
    {
        $query = '';
        foreach ($this->pay() as $key => $value) {
            $query .= "&" . $key . '=' . urlencode($value);
        }
        $query = ltrim($query, '&');

        return config('paybox.url_paybox') . '?' . $query;
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

    protected function formatMontant($montant) :int
    {
        return (int) round(100 * $montant);
    }

    static public function getPbxRetourSring() :string
    {
        $pbx_retour = [];

        foreach (self::PBX_RETOUR as $code => $variable) {
            $pbx_retour[] = $variable.':'.$code;
        }
        return implode(';', $pbx_retour);
    }

    protected function formatTextValue($value, $type, $maxLength)
    {
        /*
        AN : Alpha Numérique sans caractères spéciaux
        ANP : Alpha Numérique avec les espaces et caractères accentués
        ANS : Alpha Numérique avec caractères spéciaux
        N : Numérique uniquement
        A : Alphabétique uniquement
        */

        switch ($type) {
            default:
            case 'AN':
                $value = Tools::stripAccents($value);
                $value = preg_replace('/[^-. a-zA-Z0-9]/', '', $value);
                break;
            case 'ANP':
                $value = preg_replace('/[^ [:alpha:]]/', '', $value);
                break;
            case 'ANS':
                break;
            case 'N':
                $value = preg_replace('/[^0-9.]/', '', $value);
                break;
            case 'A':
                $value = Tools::stripAccents($value);
                $value = preg_replace('/[^A-Za-z]/', '', $value);
                break;
        }
        // Remove carriage return characters
        $value = trim(preg_replace("/\r|\n/", '', $value));

        // Cut the string when needed
        $value = mb_substr($value, 0, $maxLength, 'UTF-8');

        return $value;
    }

    public function __construct(
        protected string $reference,
        protected float $montant,
        protected string $email,
        protected string $prenom,
        protected string $nom,
        protected string $adresse = 'Avenue du paradis',
        protected string $cp = '75001',
        protected string $ville = 'Paris',
        protected int $pays_code = 250
    )
    {

    }
}
