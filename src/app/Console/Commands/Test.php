<?php

namespace PixellWeb\Paybox\app\Console\Commands;


use Illuminate\Console\Command;
use Illuminate\Validation\Rule;
use Ipsum\Admin\app\Classes\LogViewer;
use Ipsum\Reservation\app\Models\Reservation\Paiement;
use PixellWeb\Paybox\app\FormRequest\IPNResponse;
use PixellWeb\Paybox\app\PaymentRequest;
use PixellWeb\Paybox\app\Rules\Signature;

class Test extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'paybox:test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'test paybox signature';


    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {


        LogViewer::setFile('paiement.log');

        $logs = LogViewer::all();

        foreach ($logs as $log) {
            preg_match('/Traitement IPN ({.*})/', $log['text'], $output_array);

            if (isset($output_array[1])) {

                $datas = json_decode($output_array[1], true);
                $datas['query'] = $datas;

                $validator = \Validator::make($datas, [
                    'query' => ['required', 'array', new Signature()],
                    'signature' => ['required'],
                    'reference' => ['required'],
                    'erreur' => ['required'],
                    'montant' => ['nullable', 'numeric'],
                ]);
                if ($validator->fails()) {
                    dump($validator->errors()->all(), $log, $datas['query']);
                }

            }
        }

    }



}
