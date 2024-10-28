<?php

return [
    'secret_key' => env('PROD_ENC_KEY', 'default_secret_key'),
    'iv' => env('PROD_IV_KEY', 'default_iv_key')
];