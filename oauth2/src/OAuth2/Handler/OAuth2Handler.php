<?php

/**
 * This class handles all OAuth POST that have a grant_type.
 *
 */

declare(strict_types=1);

namespace OAuth2\Handler;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Diactoros\Response\JsonResponse;
use League\OAuth2\Server\Exception\OAuthServerException;

use League\OAuth2\Server\AuthorizationServer as OAuth2Server;


class OAuth2Handler implements RequestHandlerInterface
{
    /** @var OAuth2Server  */
    private $oauthServer;

    public function __construct(OAuth2Server $oauthServer)
    {
        $this->oauthServer = $oauthServer;
    }

    public function handle(ServerRequestInterface $request) : ResponseInterface
    {
        try {
            $response = new JsonResponse([]);
            return $this->oauthServer->respondToAccessTokenRequest($request, $response);
        }
        catch (OAuthServerException $ex) {
            return new JsonResponse(['oauth_exception'=>$ex->getMessage()],400);
        }
        catch (\Exception $ex) {
            return new JsonResponse(['exception'=>$ex->getMessage()],400);
        }
    }
}
