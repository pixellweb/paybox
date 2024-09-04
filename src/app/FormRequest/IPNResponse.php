<?php

namespace PixellWeb\Paybox\app\FormRequest;


use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest as HttpFormRequest;
use Illuminate\Support\Facades\Log;
use PixellWeb\Paybox\app\Rules\Signature;
use PixellWeb\Paybox\app\Tools;


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
            'erreur' => ['required'],
            'montant' => ['nullable', 'numeric'],
            'transaction_ref' => ['nullable', config('paybox.rule_transaction_unique')],
        ];
    }


    protected function prepareForValidation()
    {
        Log::channel(config('paybox.logging_channel'))->info('Traitement IPN', $this->all());
        Log::channel(config('paybox.logging_channel'))->info(Tools::getSignedData($this->all(), true));

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
