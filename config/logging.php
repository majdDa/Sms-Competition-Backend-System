<?php

use Monolog\Handler\NullHandler;
use Monolog\Handler\StreamHandler;
use Monolog\Handler\SyslogUdpHandler;

return [

    /*
    |--------------------------------------------------------------------------
    | Default Log Channel
    |--------------------------------------------------------------------------
    |
    | This option defines the default log channel that gets used when writing
    | messages to the logs. The name specified in this option should match
    | one of the channels defined in the "channels" configuration array.
    |
    */

    'default' => env('LOG_CHANNEL', 'stack'),

    /*
    |--------------------------------------------------------------------------
    | Deprecations Log Channel
    |--------------------------------------------------------------------------
    |
    | This option controls the log channel that should be used to log warnings
    | regarding deprecated PHP and library features. This allows you to get
    | your application ready for upcoming major versions of dependencies.
    |
    */

    'deprecations' => env('LOG_DEPRECATIONS_CHANNEL', 'null'),

    /*
    |--------------------------------------------------------------------------
    | Log Channels
    |--------------------------------------------------------------------------
    |
    | Here you may configure the log channels for your application. Out of
    | the box, Laravel uses the Monolog PHP logging library. This gives
    | you a variety of powerful log handlers / formatters to utilize.
    |
    | Available Drivers: "single", "daily", "slack", "syslog",
    |                    "errorlog", "monolog",
    |                    "custom", "stack"
    |
    */

    'channels' => [
        'stack' => [
            'driver' => 'stack',
            'channels' => ['single'],
            'ignore_exceptions' => false,
        ],

        'single' => [
            'driver' => 'single',
            'path' => storage_path('logs/laravel.log'),
            'level' => env('LOG_LEVEL', 'debug'),
        ],

        'daily' => [
            'driver' => 'daily',
            'path' => storage_path('logs/laravel.log'),
            'level' => env('LOG_LEVEL', 'debug'),
            'days' => 14,
        ],

        'slack' => [
            'driver' => 'slack',
            'url' => env('LOG_SLACK_WEBHOOK_URL'),
            'username' => 'Laravel Log',
            'emoji' => ':boom:',
            'level' => env('LOG_LEVEL', 'critical'),
        ],

        'papertrail' => [
            'driver' => 'monolog',
            'level' => env('LOG_LEVEL', 'debug'),
            'handler' => SyslogUdpHandler::class,
            'handler_with' => [
                'host' => env('PAPERTRAIL_URL'),
                'port' => env('PAPERTRAIL_PORT'),
            ],
        ],

        'stderr' => [
            'driver' => 'monolog',
            'level' => env('LOG_LEVEL', 'debug'),
            'handler' => StreamHandler::class,
            'formatter' => env('LOG_STDERR_FORMATTER'),
            'with' => [
                'stream' => 'php://stderr',
            ],
        ],

        'syslog' => [
            'driver' => 'syslog',
            'level' => env('LOG_LEVEL', 'debug'),
        ],

        'errorlog' => [
            'driver' => 'errorlog',
            'level' => env('LOG_LEVEL', 'debug'),
        ],

        'null' => [
            'driver' => 'monolog',
            'handler' => NullHandler::class,
        ],

        'emergency' => [
            'path' => storage_path('logs/laravel.log'),
        ],

        'sendCode' => [
            'driver' => 'daily',
            'path' => storage_path('logs/sendCode/sendCode.log'),
            'level' => env('LOG_LEVEL', 'debug'),
            'days' => 360
        ],

        'login' => [
            'driver' => 'daily',
            'path' => storage_path('logs/login/login.log'),
            'level' => env('LOG_LEVEL', 'debug'),
            'days' => 360
        ],

        'spin' => [
            'driver' => 'daily',
            'path' => storage_path('logs/spin/spin.log'),
            'level' => env('LOG_LEVEL', 'debug'),
            'days' => 360
        ],

        'syriatelTakeAction' => [
            'driver' => 'daily',
            'path' => storage_path('logs/syriatel/takeAction/syriatelTakeAction.log'),
            'level' => env('LOG_LEVEL', 'debug'),
            'days' => 360
        ],
        'mtnTakeAction' => [
            'driver' => 'daily',
            'path' => storage_path('logs/MTN/takeAction/mtnTakeAction.log'),
            'level' => env('LOG_LEVEL', 'debug'),
            'days' => 360
        ],
        'receiveMtnSms' => [
            'driver' => 'daily',
            'path' => storage_path('logs/MTN/receive/receive_mtn_sms.log'),
            'level' => env('LOG_LEVEL', 'debug'),
            'days' => 360
        ],
        'receiveSySms' => [
            'driver' => 'daily',
            'path' => storage_path('logs/syriatel/receive/receive_Sy_sms.log'),
            'level' => env('LOG_LEVEL', 'debug'),
            'days' => 360
        ],

        'Api_Act_mtn' => [
            'driver' => 'daily',
            'path' => storage_path('logs/MTN/sendAction/activationApi/call_mtn_activation_api__.log'),
            'level' => env('LOG_LEVEL', 'debug'),
            'days' => 360
        ],

        'Api_DeAct_mtn' => [
            'driver' => 'daily',
            'path' => storage_path('logs/MTN/sendAction/De_activationApi/call_mtn_DeActivation_api__.log'),
            'level' => env('LOG_LEVEL', 'debug'),
            'days' => 14
        ],
        'renewal_mtn' => [
            'driver' => 'daily',
            'path' => storage_path('logs/MTN/sendAction/renewal/renewal_mtn__.log'),
            'level' => env('LOG_LEVEL', 'debug'),
            'days' => 360
        ],

        'Api_Act_sy' => [
            'driver' => 'daily',
            'path' => storage_path('logs/syriatel/sendAction/activationApi/call_SY_activation_api__.log'),
            'level' => env('LOG_LEVEL', 'debug'),
            'days' => 14
        ],

        'Api_DeAct_sy' => [
            'driver' => 'daily',
            'path' => storage_path('logs/syriatel/sendAction/De_activationApi/call_SY_activation_api__.log'),
            'level' => env('LOG_LEVEL', 'debug'),
            'days' => 360
        ],
        'Syriatel_Teasers' => [
            'driver' => 'daily',
            'path' => storage_path('logs/syriatel/teasers/teaser_sending_log_.log'),
            'level' => env('LOG_LEVEL', 'debug'),
            'days' => 360
        ],

        'MTN_Teasers' => [
            'driver' => 'daily',
            'path' => storage_path('logs/MTN/teasers/teaser_sending_log_.log'),
            'level' => env('LOG_LEVEL', 'debug'),
            'days' => 360
        ],


        'report' => [
            'driver' => 'daily',
            'path' => storage_path('logs/report/report__.log'),
            'level' => env('LOG_LEVEL', 'debug'),
            'days' => 360
        ],


    ],

];
