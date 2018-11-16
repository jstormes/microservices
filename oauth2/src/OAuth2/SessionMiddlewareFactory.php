<?php
/**
 * Created by PhpStorm.
 * User: jstormes
 * Date: 6/19/2018
 * Time: 11:04 AM
 */

namespace OAuth2;

use Dflydev\FigCookies\SetCookie;
use Lcobucci\Clock\SystemClock;
use Lcobucci\JWT\Parser;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use PSR7Sessions\Storageless\Http\SessionMiddleware;
use Interop\Container\ContainerInterface;


class SessionMiddlewareFactory
{
    public function __invoke(ContainerInterface $container) : SessionMiddleware
    {
        $signer = new \Lcobucci\JWT\Signer\Rsa\Sha256();
        $publicKey=file_get_contents($container->get('config')['psr7-session']['public_cert']);
        $privateKey=file_get_contents($container->get('config')['psr7-session']['private_key']);
        return new SessionMiddleware(
            $signer, //new Sha256(),
            $privateKey,
            $publicKey,
            SetCookie::create('session')
                ->withSecure(false) // false on purpose, unless you have https locally
                ->withHttpOnly(false)
                ->withPath('/'),
            new Parser(),
            1200, // 20 minutes
            new SystemClock()
        );
    }
}