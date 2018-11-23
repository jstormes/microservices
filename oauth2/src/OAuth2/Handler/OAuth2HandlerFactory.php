<?php

declare(strict_types=1);

namespace OAuth2\Handler;

use League\OAuth2\Server\Grant\AuthCodeGrant;
use Psr\Container\ContainerInterface;
use Psr\Http\Server\RequestHandlerInterface;
use League\OAuth2\Server\AuthorizationServer as OAuth2Server;
use League\OAuth2\Server\Grant\PasswordGrant;
use League\OAuth2\Server\Grant\RefreshTokenGrant;
use League\OAuth2\Server\Grant\ClientCredentialsGrant;
use League\OAuth2\Server\CryptKey;
use OAuth2\Repositories\AccessTokenRepository;
use OAuth2\Repositories\ClientRepository;
use OAuth2\Repositories\ScopeRepository;
use OAuth2\Repositories\AuthCodeRepository;
use OAuth2\Repositories\RefreshTokenRepository;
use OAuth2\Repositories\UserRepository;


class OAuth2HandlerFactory
{
    /** @var ContainerInterface */
    private $container;

    /** @var AuthorizationServer */
    private $OAuth2Server;

    public function __invoke(ContainerInterface $container) : RequestHandlerInterface
    {
        $this->container = $container;

        $this->OAuth2Server = new OAuth2Server(
            $this->container->get(ClientRepository::class),
            $this->container->get(AccessTokenRepository::class),
            $this->container->get(ScopeRepository::class),
            $this->container->get(CryptKey::class),
            'lxZFUEsBCJ2Yb14IF2ygAHI5N4+ZAUXXaSeeJm6+twsUmIen'
        );

        /**
         * Comment out any of the following lines to disable that grant type.
         */
        //$this->enableAuthorizationCodeGrant(new \DateInterval('PT1H'));
        $this->enableAuthorizationCodeGrant(new \DateInterval('PT30M'));
        $this->enableClientCredentialsGrant(new \DateInterval('PT1H'));
        $this->enablePasswordGrant(new \DateInterval('PT1H'));
        $this->enableRefreshTokenGrant(new \DateInterval('PT1H'));

        return new OAuth2Handler($this->OAuth2Server);
    }

    private function enableAuthorizationCodeGrant(\DateInterval $ttl)
    {
        $this->OAuth2Server->enableGrantType(
            new AuthCodeGrant(
                $this->container->get(AuthCodeRepository::class),
                $this->container->get(RefreshTokenRepository::class),
                new \DateInterval('PT10M')
            ),
            $ttl
        );
    }

    private function enableClientCredentialsGrant(\DateInterval $ttl)
    {
        $this->OAuth2Server->enableGrantType(
            new ClientCredentialsGrant(),
            $ttl
        );
    }

    private function enablePasswordGrant(\DateInterval $ttl)
    {
        $grant = new PasswordGrant(
            $this->container->get(UserRepository::class),
            $this->container->get(RefreshTokenRepository::class)
        );
        $grant->setRefreshTokenTTL(new \DateInterval('P1M'));

        $this->OAuth2Server->enableGrantType(
            $grant,
            $ttl
        );
    }

    private function enableRefreshTokenGrant(\DateInterval $ttl)
    {
        $grant = new RefreshTokenGrant($this->container->get(RefreshTokenRepository::class));
        $grant->setRefreshTokenTTL(new \DateInterval('P1M'));

        $this->OAuth2Server->enableGrantType(
            $grant,
            $ttl
        );
    }

}
