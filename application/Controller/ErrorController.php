<?php

namespace Backend\Controller;

use Backend\Libs\Util;

class ErrorController
{
    public function index()
    {
        Util::returnJson(401, true, "Erro não identificado. Por favor, contate um administrador");
    }
}