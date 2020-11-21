<?php

namespace Backend\Controller;

use Backend\Libs\Util;


class ApiController
{   
    public function __construct()
    {
        $headers = getallheaders();
        $token = (isset($headers['token-security'])) ? $headers['token-security'] : false;
        
        Util::protect($token);
    }
   
}