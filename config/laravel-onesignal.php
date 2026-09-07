<?php

return [

    /*
    |--------------------------------------------------------------------------
    | App ID
    |--------------------------------------------------------------------------
    |
    | Found under Settings > Keys & IDs in your OneSignal dashboard.
    |
    */
    'app_id' => env('ONESIGNAL_APP_ID', ''),

    /*
    |--------------------------------------------------------------------------
    | REST API Key
    |--------------------------------------------------------------------------
    |
    | Found under Settings > Keys & IDs in your OneSignal dashboard. Sent as
    | an "Authorization: Basic {key}" header on every request.
    |
    */
    'rest_api_key' => env('ONESIGNAL_REST_API_KEY', ''),

];
