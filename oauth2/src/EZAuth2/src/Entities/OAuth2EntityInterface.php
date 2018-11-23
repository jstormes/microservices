<?php
/**
 * Created by PhpStorm.
 * User: jstormes
 * Date: 8/3/2018
 * Time: 10:26 AM
 */

namespace EZAuth2\Entities;

interface OAuth2EntityInterface
{
    public function getClaims();

    public function setJWT($jwt);
    public function getJWT();
}