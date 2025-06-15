<?php

return [
    'app_config' => env('FIREBASE_CONFIG_NAME'),
    'app_scope' => env('FIREBASE_APP_SCOPE', 'https://www.googleapis.com/auth/firebase.messaging'),
    'push_url' => env('FIREBASE_PUSH_URL')
];