<?php
/**
 * Created by PhpStorm.
 * User: jstormes
 * Date: 11/14/2018
 * Time: 1:16 PM
 */

$server = new swoole_http_server("127.0.0.1", 9501, SWOOLE_PROCESS, SWOOLE_SOCK_TCP | SWOOLE_SSL);
// setup the location of ssl cert files and key files
$server->set([
    'ssl_cert_file' => $ssl_dir . '/ssl.crt',
    'ssl_key_file' => $ssl_dir . '/ssl.key',
    'open_http2_protocol' => true, // Enable HTTP2 protocol
]);