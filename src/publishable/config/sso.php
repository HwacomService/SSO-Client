<?php

return [
    // SSO credentials
    'sso_enable'    => env('SSO_ENABLE',false),
    'client_secret' => env("SSO_CLIENT_SECRET"),
    'callback'      => env("SSO_CLIENT_CALLBACK"),
    'sso_host'      => env("SSO_HOST")
];
