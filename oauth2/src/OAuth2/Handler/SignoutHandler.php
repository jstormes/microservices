<?php
/**
 * Created by PhpStorm.
 * User: jstormes
 * Date: 6/6/2018
 * Time: 5:23 PM
 */

declare(strict_types=1);

namespace OAuth2\Handler;


use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Diactoros\Response\HtmlResponse;
use Zend\Expressive\Plates\PlatesRenderer;
use PSR7Sessions\Storageless\Http\SessionMiddleware;



class SignoutHandler implements RequestHandlerInterface
{
    /** @var PlatesRenderer  */
    private $templateEngine;

    public function __construct( PlatesRenderer $templateEngine )
    {
        $this->templateEngine = $templateEngine;
    }

    public function handle(ServerRequestInterface $request) : ResponseInterface
    {
        /* @var \PSR7Sessions\Storageless\Session\DefaultSessionData $session */
        $session = $request->getAttribute(SessionMiddleware::SESSION_ATTRIBUTE);

        $this->remove($session, 'user');
        $this->remove($session, 'authorized');

        return new HtmlResponse($this->templateEngine->render('signout::default'));
    }

    private function remove($session, $key) {
        if ($session->has($key)) {
            $session->remove($key);
        }
    }
}