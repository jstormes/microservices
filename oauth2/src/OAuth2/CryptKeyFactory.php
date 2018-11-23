<?php
/**
 * Created by PhpStorm.
 * User: jstormes
 * Date: 6/8/2018
 * Time: 2:55 PM
 */

namespace OAuth2;

use Psr\Container\ContainerInterface;
use League\OAuth2\Server\CryptKey;


class CryptKeyFactory
{

    public function __invoke(ContainerInterface $container) : CryptKey
    {
        return new CryptKey($container->get('config')['oauth2']['private_key'], null, false);
    }
}