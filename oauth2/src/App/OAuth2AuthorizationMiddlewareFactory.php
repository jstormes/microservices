<?php
/**
 * Created by PhpStorm.
 * User: jstormes
 * Date: 6/22/2018
 * Time: 2:58 PM
 */

declare(strict_types=1);

namespace App;


use EZAuth2\Middleware\EZAuth2Middleware;
use Interop\Container\ContainerInterface;

class OAuth2AuthorizationMiddlewareFactory
{

    public function __invoke(ContainerInterface $container) : EZAuth2Middleware
    {
        return new EZAuth2Middleware('oauth2.loopback.world', 'myawesomeapp', 'abc123', 'scope1 scope2');
    }
}