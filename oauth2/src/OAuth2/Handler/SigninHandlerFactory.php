<?php
/**
 * Created by PhpStorm.
 * User: jstormes
 * Date: 6/6/2018
 * Time: 5:23 PM
 */

declare(strict_types=1);

namespace OAuth2\Handler;

use App\Entities\ClientEntity;
use Psr\Container\ContainerInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Expressive\Template\TemplateRendererInterface;
use App\Repositories\UserRepository;
use Zend\Expressive\Router\RouterInterface;


class SigninHandlerFactory
{
    public function __invoke(ContainerInterface $container) : RequestHandlerInterface
    {
        $templateRenderer = $container->get(TemplateRendererInterface::class);
        $userRepository = $container->get(UserRepository::class);
        $clientEntity = $container->get(ClientEntity::class);
        $router = $container->get(RouterInterface::class);
        $redis = $container->get('redis');

        return new SigninHandler($templateRenderer, $userRepository, $clientEntity, $router, $redis);
    }
}