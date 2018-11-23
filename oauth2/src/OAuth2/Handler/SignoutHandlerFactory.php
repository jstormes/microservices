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


class SignoutHandlerFactory
{
    public function __invoke(ContainerInterface $container) : RequestHandlerInterface
    {
        $template = $container->get(TemplateRendererInterface::class);

        return new SignoutHandler($template);
    }
}