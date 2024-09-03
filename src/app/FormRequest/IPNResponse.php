<?php

namespace PixellWeb\Paybox\app\FormRequest;


use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest as HttpFormRequest;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use PixellWeb\Paybox\app\Rules\Signature;


class IPNResponse extends HttpFormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'query' => ['required', 'array', new Signature()],
            'signature' => ['required'],
            'reference' => ['required', config('paybox.rule_exists')],
            'erreur' => ['required', Rule::in(['00000'])],
            'montant' => ['required', 'numeric'],
            'transaction_ref' => ['required'/*, Rule::unique(Paiement::class, 'transaction_ref')*/],
            'autorisation_ref' => ['required'],
        ];
    }


    protected function prepareForValidation()
    {
        Log::channel(config('paybox.logging_channel'))->info('Traitement IPN', $this->all());
        Log::channel(config('paybox.logging_channel'))->info($this->getQueryString());

        $this->merge([
            'query' => $this->all()
        ]);
    }


    protected function failedValidation(\Illuminate\Validation\Validator|Validator $validator)
    {
        Log::channel(config('paybox.logging_channel'))->critical($validator->messages());

        abort(200);
    }


}
