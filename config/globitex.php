<?php

return [
    // API key
    'key' => env('GLOBITEX_KEY'),

    // API Message signing secret
    'message_secret' => env('GLOBITEX_MESSAGE_SECRET'),

    // API Outgoing transaction signing secret
    'outgoing_secret' => env('GLOBITEX_OUTGOING_SECRET'),
];
