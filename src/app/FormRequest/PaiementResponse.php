<?php

namespace PixellWeb\Paybox\app\FormRequest;


use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest as HttpFormRequest;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use PixellWeb\Paybox\app\Rules\Signature;
use PixellWeb\Paybox\app\Tools;


class PaiementResponse extends HttpFormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'erreur' => ['required', Rule::in(['00000'])],
            'montant' => ['required'],
            'autorisation_ref' => ['required']
        ];
    }


    protected function failedValidation(\Illuminate\Validation\Validator|Validator $validator): void
    {

        Log::channel(config('paybox.logging_channel'))->critical($validator->messages());

        abort(200);
    }


}
