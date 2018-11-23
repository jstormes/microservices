<?php
/**
 * Created by PhpStorm.
 * User: jstormes
 * Date: 6/6/2018
 * Time: 5:23 PM
 */

declare(strict_types=1);

namespace OAuth2\Handler;

use OAuth2\Entities\UserEntity;
use OAuth2\Entities\ClientEntity;
use OAuth2\Repositories\UserRepository;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Diactoros\Response\HtmlResponse;
use League\OAuth2\Server\AuthorizationServer as OAuth2Server;
use Zend\Expressive\Plates\PlatesRenderer;
use PSR7Sessions\Storageless\Http\SessionMiddleware;
use PSR7Sessions\Storageless\Session\DefaultSessionData;
use League\OAuth2\Server\RequestTypes\AuthorizationRequest;
use Zend\Diactoros\Response\RedirectResponse;
use Zend\Expressive\Helper\UrlHelper;


class AuthorizationHandler implements RequestHandlerInterface
{
    /** @var PlatesRenderer  */
    private $templateEngine;

    /** @var OAuth2Server */
    private $OAuth2Server;

    /** @var UrlHelper  */
    private $urlHelper;

    /** @var DefaultSessionData */
    private $session;

    /** @var UserRepository  */
    private $userRepository;

    /** @var ClientEntity  */
    private $clientEntity;

    public function __construct( PlatesRenderer $templateEngine,
                                 OAuth2Server $OAuth2Server,
                                 UrlHelper $urlHelper,
                                 UserRepository $userRepository,
                                 ClientEntity $clientEntity)
    {
        $this->templateEngine = $templateEngine;
        $this->OAuth2Server = $OAuth2Server;
        $this->urlHelper = $urlHelper;

        $this->userRepository=$userRepository;
        $this->clientEntity = $clientEntity;
    }

    public function handle(ServerRequestInterface $request) : ResponseInterface
    {
        /* @var \PSR7Sessions\Storageless\Session\DefaultSessionData $session */
        $session = $request->getAttribute(SessionMiddleware::SESSION_ATTRIBUTE);

        $formData = [];

        if ($request->getMethod()==="POST") {

            $formData = array_merge($formData, $request->getParsedBody());

            // TODO: Research why grant type and ClientEntity are passed, may impact this design.
            $user = $this->userRepository->getUserEntityByUserCredentials(
                $formData['username'],
                $formData['password'],
                'password',
                $this->clientEntity);

            if ($user instanceof UserEntity) {
                $session->set('user', serialize($user));
            }
        }

        if ($session->has('user')) {

            /** @var UserEntity $user */
            $user = unserialize($session->get('user'));

            if ($user instanceof UserEntity){

                $authRequest = $this->OAuth2Server->validateAuthorizationRequest($request);
                $authRequest->setUser($user);
                $authRequest->setAuthorizationApproved(true);

                $response = new HtmlResponse('');
                return $this->OAuth2Server->completeAuthorizationRequest($authRequest, $response);
            }
        }

        return new HtmlResponse($this->templateEngine->render('oauth2::signin', $formData));
    }

}