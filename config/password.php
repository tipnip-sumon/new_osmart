<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Password Email Configuration
    |--------------------------------------------------------------------------
    |
    | This option controls whether the new password should be included in
    | password change notification emails. Setting this to false is more
    | secure as passwords won't be transmitted via email.
    |
    */

    'include_password_in_email' => env('INCLUDE_PASSWORD_IN_EMAIL', true),

    /*
    |--------------------------------------------------------------------------
    | Password Change Email Settings
    |--------------------------------------------------------------------------
    |
    | Configure various settings for password change notifications
    |
    */

    'email_settings' => [
        'send_notification' => env('SEND_PASSWORD_CHANGE_EMAIL', true),
        'include_security_tips' => true,
        'include_device_info' => true,
        'auto_delete_suggestion' => true,
    ],

];
