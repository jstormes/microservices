<?php

declare(strict_types=1);

namespace OAuth2;

use League\OAuth2\Server\CryptKey;
use http\Exception;
use PSR7Sessions\Storageless\Http\SessionMiddleware;


/**
 * The configuration provider for the App module
 *
 * @see https://docs.zendframework.com/zend-component-installer/
 */
class ConfigProvider
{
    /** @var string  */
    private $publicCertPath;

    /** @var string  */
    private $privateKeyPath;

    /**
     * Returns the configuration array
     *
     * To add a bit of a structure, each section is defined in a separate
     * method which returns an array with its configuration.
     *
     */
    public function __invoke() : array
    {
        $this->setTlsPaths();
        return [
            'dependencies'              => $this->getDependencies(),
            'templates'                 => $this->getTemplates(),
            'zend-expressive-swoole'    => $this->getSwooleConfig(),
            'oauth2'                    => $this->getOAuth2Config(),
            'psr7-session'              => $this->getPsr7SessionConfig()
        ];
    }

    /**
     * Sets the paths for the TLS (SSL) key and certificate.
     * SPOOKY ACTION AT A DISTANCE: This is picked up by SessionMiddlewareFactory, OAuth2, and Swoole.
     * TODO: This should be updated by Let's Encrypts ACME2 protocol.
     *
     * @throws \Exception
     */
    private function setTlsPaths()
    {
        $this->publicCertPath = realpath('tls/certs/wild.loopback.world.cert');
        $this->privateKeyPath = realpath('tls/private/wild.loopback.world.key');

        if (!file_exists($this->publicCertPath)) {
            throw new \Exception('Cannot find path for TLS (SSL) Certificate.');
        }

        if (!file_exists($this->privateKeyPath)) {
            throw new \Exception('Cannot find path for TLS (SSL) Key.');
        }
    }

    /**
     * Returns the container dependencies
     */
    public function getDependencies() : array
    {

        return [
            'invokables' => [
                UserRepository::class => UserRepository::class,
                Entities\ClientEntity::class => Entities\ClientEntity::class,
                Repositories\ClientRepository::class => Repositories\ClientRepository::class,
                Repositories\AccessTokenRepository::class => Repositories\AccessTokenRepository::class,
                Repositories\ScopeRepository::class => Repositories\ScopeRepository::class,
                Repositories\UserRepository::class => Repositories\UserRepository::class,
                Repositories\RefreshTokenRepository::class => Repositories\RefreshTokenRepository::class,
                Repositories\AuthCodeRepository::class => Repositories\AuthCodeRepository::class,
            ],
            'factories'  => [
                SessionMiddleware::class => SessionMiddlewareFactory::class,
                CryptKey::class => CryptKeyFactory::class,

                Handler\OAuth2Handler::class => Handler\OAuth2HandlerFactory::class,
                Handler\AuthorizationHandler::class => Handler\AuthorizationHandlerFactory::class,
//                Handler\SigninHandler::class => Handler\SigninHandlerFactory::class,
//                Handler\SignoutHandler::class => Handler\SignoutHandlerFactory::class,
//                HtmlGateway::class => HtmlGatewayFactory::class,

            ]
        ];
    }

    /**
     * Returns the templates configuration
     */
    public function getTemplates() : array
    {
        return [
            'paths' => [
                'oauth2'    => [__DIR__ . '/templates/oauth2']
            ],
        ];
    }

    public function getOAuth2Config() : array
    {
        return [
            'public_cert' => $this->publicCertPath,
            'private_key' => $this->privateKeyPath
        ];
    }

    public function getSwooleConfig() : array
    {
        return [
            'swoole-http-server' => [
                'options' => [
                    'ssl_cert_file' => $this->publicCertPath,
                    'ssl_key_file' => $this->privateKeyPath,
                ],
            ]
        ];
    }

    public function getPsr7SessionConfig() : array
    {
        return [
            'public_cert' => $this->publicCertPath,
            'private_key' => $this->privateKeyPath
        ];
    }
}
