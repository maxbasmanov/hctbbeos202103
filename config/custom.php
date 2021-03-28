<?php

return [
    'eos' => [
        'wallet' => env('EOS_WALLET'),
		'project_contract' => env('EOS_PROJECT_CONTRACT'),
		'token_contract' => env('EOS_TOKEN_CONTRACT'),
		'token_name' => env('EOS_TOKEN_NAME'),
        'password' => env('EOS_PWD'),
        'keys' => env('EOS_KEYS'),
        'cleos' => env('EOS_CLEOS_ENDPOINT'),
        'keosd' => env('EOS_KEOSD_ENDPOINT'),
    ],
];
