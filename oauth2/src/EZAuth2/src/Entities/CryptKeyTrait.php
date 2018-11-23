<?php
/**
 * Created by PhpStorm.
 * User: jstormes
 * Date: 8/15/2018
 * Time: 3:14 PM
 */

namespace EZAuth2\Entities;

use EZAuth2\Crypt\CryptKey;
use Lcobucci\JWT\Signer\Rsa\Sha256;

trait CryptKeyTrait
{
    /** @var CryptKey */
    private $key = null;

    public function setKey(CryptKey $key)
    {
        $this->key = $key;
    }

    public function isTokenSigned($token)
    {
        try {
            if ($token->verify(new Sha256(), $this->key->getKeyPath()) === false) {
                throw new \Exception('Access token could not be verified');
            }
        } catch (\BadMethodCallException $exception) {
            throw new \Exception('Access token is not signed');
        }

        return true;
    }

}