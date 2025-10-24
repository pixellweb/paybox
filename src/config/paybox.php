<?php

return [


    'test' => env('PAYBOX_TEST',false),

    'test_ipn_local' => env('PAYBOX_TEST_IPN_LOCAL',false),

    'url_paybox' => (env('PAYBOX_TEST') and env('APP_DEBUG')) ? 'https://preprod-tpeweb.paybox.com/cgi/MYchoix_pagepaiement.cgi' : 'https://tpeweb.paybox.com/cgi/MYchoix_pagepaiement.cgi',

    'secret' => env('PAYBOX_SECRET','0123456789ABCDEF0123456789ABCDEF0123456789ABCDEF0123456789ABCDEF0123456789ABCDEF0123456789ABCDEF0123456789ABCDEF0123456789ABCDEF'),
    'site' => env('PAYBOX_SITE','1999888'),
    'rang' => env('PAYBOX_RANG','32'),
    'identifiant' => env('PAYBOX_ID','110647233'),

    'devise' => 978,

    'public_key' => '-----BEGIN PUBLIC KEY-----
MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEA16QmkNXsa4XjKqhMCF9k
L4gboAXZrpX9AXJhQFtpu65SEg7Ejht+5J7vztnZQrJ6o+Gy/N31Mj0+T/937OlO
Z/xH/SR40f93LuIYPXykoAelUWVJYe4HqLvtBKskOHBmy4KGYNB1QDtyFoYt4aSo
aBzPYJrjpoLlCqKhU4mnKxVZih4ZYvBUnrCEKt86VeTLUVlXy/xwyTNieiYGM/oV
1PpCUlVfLqA7t2GQRZTrdyUwK8zEbMfFOA5acdX1exIGV8gFnj/BUFndA0SdMhfo
EDe9RFHELMEHxmSZjwqSyX81uNoIshY5YjMtJ6puCI8q7VJnB3+9W5OUll1127pt
8wIDAQAB
-----END PUBLIC KEY-----',

    'logging_channel' => 'paiement',

    'rule_exists' => 'exists:reservations,reference',
    'rule_transaction_unique' => 'unique:paiements,transaction_ref',

    'url_annule'     => 'paiement.refuse',
    'url_effectue'     => 'reservation.confirmation',
    'url_attente'      => 'reservation.confirmation',
    'url_refuse'       => 'paiement.refuse',
    'url_repondre_a'   => 'paiement.ipn',

];
