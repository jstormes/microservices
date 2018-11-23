<?php
/**
 * Created by PhpStorm.
 * User: jstormes
 * Date: 6/6/2018
 * Time: 5:23 PM
 */

declare(strict_types=1);

namespace OAuth2\Handler;

use Psr\Container\ContainerInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Expressive\Template\TemplateRendererInterface;
use League\OAuth2\Server\AuthorizationServer as OAuth2Server;
use League\OAuth2\Server\Grant\AuthCodeGrant;
use League\OAuth2\Server\Grant\ImplicitGrant;
use League\OAuth2\Server\CryptKey;
use OAuth2\Repositories\AccessTokenRepository;
use OAuth2\Repositories\ClientRepository;
use OAuth2\Repositories\ScopeRepository;
use OAuth2\Repositories\AuthCodeRepository;
use OAuth2\Repositories\RefreshTokenRepository;
use OAuth2\Repositories\UserRepository;
use OAuth2\Entities\ClientEntity;
use Zend\Expressive\Helper\UrlHelper;
use Zend\Expressive\Router\RouterInterface;



class AuthorizationHandlerFactory
{
    /** @var ContainerInterface */
    private $container;

    /** @var AuthorizationServer */
    private $OAuth2Server;

    public function __invoke(ContainerInterface $container): RequestHandlerInterface
    {
        $template = $container->get(TemplateRendererInterface::class);
        $router = $container->get(RouterInterface::class);
        $urlHelper = new UrlHelper($router);

        $this->container = $container;

        $this->OAuth2Server = new OAuth2Server(
            $this->container->get(ClientRepository::class),
            $this->container->get(AccessTokenRepository::class),
            $this->container->get(ScopeRepository::class),
            $this->container->get(CryptKey::class),
            'lxZFUEsBCJ2Yb14IF2ygAHI5N4+ZAUXXaSeeJm6+twsUmIen'
        );

        $this->enableAuthorizationCodeGrant();
        $this->enableImplicitGrant();

        $userRepository = $container->get(UserRepository::class);
        $clientEntity = $container->get(ClientEntity::class);

        return new AuthorizationHandler($template, $this->OAuth2Server, $urlHelper, $userRepository, $clientEntity);
    }

    private function enableImplicitGrant()
    {
        $this->OAuth2Server->enableGrantType(
            new ImplicitGrant(new \DateInterval('PT1H')),
            new \DateInterval('PT1H') // access tokens will expire after 1 hour
        );
    }

    private function enableAuthorizationCodeGrant()
    {

        $this->OAuth2Server->enableGrantType(
            new AuthCodeGrant(
                $this->container->get(AuthCodeRepository::class),
                $this->container->get(RefreshTokenRepository::class),
                new \DateInterval('PT1H')
            ),
            new \DateInterval('PT1H')
        );
    }

}
