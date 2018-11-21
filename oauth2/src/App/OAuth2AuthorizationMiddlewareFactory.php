<?php
/**
 * Created by PhpStorm.
 * User: jstormes
 * Date: 6/22/2018
 * Time: 2:58 PM
 */

namespace App;


use EZAuth2\Crypt\CryptKey;
use League\OAuth2\Client\Provider\GenericProvider;
use EZAuth2\Middleware\OAuth2AuthorizationMiddleware;
use EZAuth2\Entities\OAuth2Entity;
use Interop\Container\ContainerInterface;

class OAuth2AuthorizationMiddlewareFactory
{
    public function __invoke(ContainerInterface $container) : OAuth2AuthorizationMiddleware
    {
        $options = [
            'clientId'                => 'myawesomeapp',    // The client ID assigned to you by the provider
            'clientSecret'            => 'abc123',   // The client password assigned to you by the provider
            'redirectUri'             => 'https://'.$_SERVER['HTTP_HOST'].'/app',
            'urlAuthorize'            => 'https://'.$_SERVER['HTTP_HOST'].'/oauth2/auth',
            'urlAccessToken'          => 'https://'.$_SERVER['HTTP_HOST'].'/oauth2',
            'urlResourceOwnerDetails' => 'https://'.$_SERVER['HTTP_HOST'].'/oauth2/user/resource'
        ];

        $provider = new GenericProvider($options);

        $OAuth2Prototype = new OAuth2Entity();

        $cryptKey = new CryptKey($container->get('config')['psr7-session']['public_cert'], null, false);
        $OAuth2Prototype->setKey($cryptKey);

        return new OAuth2AuthorizationMiddleware(
            $OAuth2Prototype,
            $provider
        );
    }
}