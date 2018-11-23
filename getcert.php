<?php

// https://stackoverflow.com/questions/3081042/how-to-get-ssl-certificate-info-with-curl-in-php

$g = stream_context_create (array("ssl" => array("capture_peer_cert" => true)));
$r = stream_socket_client("ssl://www.google.com:443", $errno, $errstr, 30,
    STREAM_CLIENT_CONNECT, $g);
$cont = stream_context_get_params($r);

echo "\n\n";

$certinfo = openssl_x509_parse($cont['options']['ssl']['peer_certificate']);
print_r($certinfo);

openssl_x509_export($cont['options']['ssl']['peer_certificate'], $out);

echo $out;

echo "\n";

