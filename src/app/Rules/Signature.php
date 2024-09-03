<?php

namespace PixellWeb\Paybox\app\Rules;

use Illuminate\Contracts\Validation\Rule;


class Signature implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string $attribute
     * @param  mixed $value
     * @return bool
     * @throws \Exception
     */
    public function passes($attribute, $value)
    {
        $string_param = '';
        $signature='';
        foreach ($value as $key => $data) {
            if ($key == Paybox::PBX_RETOUR_SIGNATURE) {
                $signature = base64_decode($data);
                continue;
            }
            $string_param .= "&".$key.'='.$data;
        }
        $string_param = ltrim($string_param, '&');

        $public_key = openssl_pkey_get_public(config('paybox.public_key'));
        return openssl_verify($string_param, $signature, $public_key);
    }



    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Problème de sécurité. La signature n\'est pas valide.';
    }
}
