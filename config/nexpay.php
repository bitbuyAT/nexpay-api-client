<?php

return [
    // API key
    'key' => env('NEXPAY_KEY'),

    // API Message signing secret
    'message_secret' => env('NEXPAY_MESSAGE_SECRET'),

    // API Outgoing transaction signing secret
    'outgoing_secret' => env('NEXPAY_OUTGOING_SECRET'),
];
