<?php

namespace PixellWeb\Paybox\app\Rules;

use Exception;
use Illuminate\Contracts\Validation\Rule;
use PixellWeb\Paybox\app\PaymentRequest;
use PixellWeb\Paybox\app\PaymentResponse;


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
     * @throws Exception
     */
    public function passes($attribute, $value): bool
    {
        return (new PaymentResponse($value[PaymentRequest::PBX_RETOUR_SIGNATURE] ?? null, $value))->verifySignature();
    }



    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message(): string
    {
        return 'Problème de sécurité. La signature n\'est pas valide.';
    }
}
