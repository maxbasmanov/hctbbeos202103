<?php

return [
    'eos' => [
        'wallet' => env('EOS_WALLET'),
		'contract' => env('EOS_CONTRACT'),
        'password' => env('EOS_PWD'),
        'keys' => env('EOS_KEYS'),
        'cleos' => env('EOS_CLEOS_ENDPOINT'),
        'keosd' => env('EOS_KEOSD_ENDPOINT'),
    ],
];
