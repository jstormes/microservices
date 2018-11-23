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
use App\Entities\UserEntity;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Diactoros\Response\HtmlResponse;
use Zend\Expressive\Router\RouterInterface;

use Zend\Expressive\Plates\PlatesRenderer;
use App\Repositories\UserRepository;

use Zend\Diactoros\Response\RedirectResponse;

use Zend\Expressive\Helper\UrlHelper;

use PSR7Sessions\Storageless\Http\SessionMiddleware;

use League\OAuth2\Server\RequestTypes\AuthorizationRequest;


class SigninHandler implements RequestHandlerInterface
{
    /** @var PlatesRenderer  */
    private $templateRenderer;

    /** @var UserRepository  */
    private $userRepository;

    /** @var ClientEntity  */
    private $clientEntity;

    /** @var RouterInterface */
    private $router;

    /** @var \Redis  */
    private $redis;

    public function __construct( PlatesRenderer $templateRenderer,
                                 UserRepository $userRepository,
                                 ClientEntity $clientEntity,
                                 RouterInterface $router,
                                 \Redis $redis)
    {
        $this->templateRenderer = $templateRenderer;
        $this->userRepository = $userRepository;
        $this->clientEntity = $clientEntity;
        $this->router = $router;
        $this->redis = $redis;
    }

    public function handle(ServerRequestInterface $request) : ResponseInterface
    {
        /* @var \PSR7Sessions\Storageless\Session\DefaultSessionData $session */
        $session = $request->getAttribute(SessionMiddleware::SESSION_ATTRIBUTE);

        $session->remove('user');

        $data = [
            'error_msg'=> null,
        ];

        // if post check login.
        if ($request->getMethod()==="POST") {
            $data['error_msg'] = 'Unknown Error';

            // serial data into entity.
            $data = array_merge($data, $request->getParsedBody());

            try {
                $user = $this->userRepository->getUserEntityByUserCredentials($data['username'], $data['password'], 'password', $this->clientEntity);

                if ($user instanceof UserEntity) {

                    $session->set('user', serialize($user));
                    $user->getUserName();
                    $this->redis->set($user->getUserName(),serialize($user));
                    $urlHelper = new UrlHelper($this->router);
                    $newUrl = $urlHelper->generate('oauth2.auth', [], $request->getQueryParams());
                    return new RedirectResponse($newUrl);
                }
            }
            catch (\Exception $ex){
                $data['error_msg'] = $ex->getMessage();
            }

            // else set errors.
        }
        // show login page.
        return new HtmlResponse($this->templateRenderer->render('oauth2::signin', $data));
    }
}