<?php
/**
 * Created by PhpStorm.
 * User: jstormes
 * Date: 10/29/2018
 * Time: 2:47 PM
 */

declare(strict_types=1);

return [
    'zend-expressive-swoole' => [
        'swoole-http-server' => [
            'host' => '0.0.0.0',
            'port' => 8443,
            // SWOOLE_BASE or SWOOLE_PROCESS; SWOOLE_BASE is the default
            'mode' => SWOOLE_BASE,
            'protocol' => SWOOLE_SOCK_TCP | SWOOLE_SSL, // SSL-enable the server
            'options' => [
                // Set the SSL certificate and key paths for SSL support:
                // These are set in the ConfigProvider.php for OAuth2
                //'ssl_cert_file' => 'tls/certs/wild.loopback.world.cert',
                //'ssl_key_file' => 'tls/private/wild.loopback.world.key',
                // Available in Swoole 4.1 and up; enables coroutine support
                // for most I/O operations:
                'enable_coroutine' => true,
                // Enable HTTP/2
                'open_http2_protocol' => true,
            ],
        ],
    ],
];