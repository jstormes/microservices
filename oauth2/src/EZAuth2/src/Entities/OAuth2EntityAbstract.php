<?php
/**
 * Created by PhpStorm.
 * User: jstormes
 * Date: 8/3/2018
 * Time: 11:09 AM
 */

namespace EZAuth2\Entities;

use Lcobucci\JWT\Parser;
use Lcobucci\JWT\ValidationData;
use EZAuth2\Exception\ExpiredToken;


abstract class OAuth2EntityAbstract implements OAuth2EntityInterface
{
    /** @var string */
    private $jwt;

    /** @var array token cache */
    private $tokens = [];

    public function getClaims()
    {
        $this->parseToken($this->jwt)->getClaims();
    }

    public function setJWT($jwt)
    {
        if ($this->isTokenValid($jwt)){
            $this->jwt = $jwt;
            return $this;
        }

        throw new \Exception('Invalid JWT.');
    }

    public function getJWT()
    {
        return $this->jwt;
    }

    private function isTokenValid($jwt)
    {
        $token = $this->parseToken($jwt);

        if ($this->isTokenSigned($token) === false) {
            return false;
        }

        if ($this->isTokenExpired($token)) {
            return false;
        }

        return true;
    }

    private function parseToken($jwt)
    {
        if (isset($this->tokens[$jwt]))
            return $this->tokens[$jwt];

        $this->tokens[$jwt] = (new Parser())->parse($jwt);
        return $this->tokens[$jwt];
    }

    /**
     * Check the token for it's signature.
     *
     * @param $token
     * @return mixed
     */
    abstract function isTokenSigned($token);

    private function isTokenExpired($token)
    {
        $data = new ValidationData();
        $data->setCurrentTime(time());

        if ($token->validate($data) === false) {
            throw new ExpiredToken('Access token is invalid');
        }

        return false;
    }

}