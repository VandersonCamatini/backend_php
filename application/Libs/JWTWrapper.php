<?php

namespace Backend\Libs;

use Firebase\JWT\JWT;
class JWTWrapper
{
    const KEY = '21nr981bh74hfbu';

    /**
     * @param array $options
     */
    public static function encode($options)
    {
        return JWT::encode($options, self::KEY);
    }

    /**
     * @param string $jwt token
     */
    public static function decode($jwt)
    {
        return JWT::decode($jwt, self::KEY, ['HS256']);
    }
}