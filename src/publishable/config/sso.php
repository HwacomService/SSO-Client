<?php

return [
    // SSO credentials
    'sso_enable'    => env('SSO_ENABLE'),
    'client_secret' => env("SSO_CLIENT_SECRET"),
    'callback'      => env("SSO_CLIENT_CALLBACK"),
    'sso_host'      => env("SSO_HOST"),
    'user_model'    => env("USER_MODEL",'\App\Models\User')
];
