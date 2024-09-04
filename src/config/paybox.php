<?php

return [


    'test' => env('PAYBOX_TEST',true),

    'url_paybox' => env('PAYBOX_TEST') ? 'https://preprod-tpeweb.paybox.com/cgi/MYchoix_pagepaiement.cgi' : 'https://tpeweb.paybox.com/cgi/MYchoix_pagepaiement.cgi',

    'secret' => env('PAYBOX_SECRET','0123456789ABCDEF0123456789ABCDEF0123456789ABCDEF0123456789ABCDEF0123456789ABCDEF0123456789ABCDEF0123456789ABCDEF0123456789ABCDEF'),
    'site' => env('PAYBOX_SITE','1999888'),
    'rang' => env('PAYBOX_RAND','43'),
    'identifiant' => env('PAYBOX_ID','107975626'),

    'devise' => 978,

    'public_key' => '-----BEGIN PUBLIC KEY-----
MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQDe+hkicNP7ROHUssGNtHwiT2Ew
HFrSk/qwrcq8v5metRtTTFPE/nmzSkRnTs3GMpi57rBdxBBJW5W9cpNyGUh0jNXc
VrOSClpD5Ri2hER/GcNrxVRP7RlWOqB1C03q4QYmwjHZ+zlM4OUhCCAtSWflB4wC
Ka1g88CjFwRw/PB9kwIDAQAB
-----END PUBLIC KEY-----',

    'logging_channel' => 'paiement',

    'rule_exists' => 'exists:reservations,reference',
    'rule_transaction_unique' => 'unique:paiements,transaction_ref',

    'url_annule'     => 'paiement.refuse',
    //'url_effectue'     => 'reservation.confirmation',
    'url_effectue'     => 'paiement.ipn',
    'url_attente'      => 'reservation.confirmation',
    'url_refuse'       => 'paiement.refuse',
    'url_repondre_a'   => 'paiement.ipn',

];
