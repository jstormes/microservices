<?php
/**
 * Created by PhpStorm.
 * User: jstormes
 * Date: 10/29/2018
 * Time: 2:47 PM
 */



return [
    'zend-expressive-swoole' => [
        'swoole-http-server' => [
            'host' => '0.0.0.0',
            'port' => 8443,
            'mode' => SWOOLE_BASE, // SWOOLE_BASE or SWOOLE_PROCESS;
            // SWOOLE_BASE is the default
            'protocol' => SWOOLE_PROCESS | SWOOLE_SOCK_TCP | SWOOLE_SSL, // SSL-enable the server
            'options' => [
                // Set the SSL certificate and key paths for SSL support:
                'ssl_cert_file' => 'tls/certs/loopback.world.cert.pem',
                'ssl_key_file' => 'tls/private/loopback.world.privkey.pem',
                // Available in Swoole 4.1 and up; enables coroutine support
                // for most I/O operations:
                'enable_coroutine' => true,
                'open_http2_protocol' => true,
            ],
        ],
    ],
];