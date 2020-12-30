<?php

namespace Backend\Controller;

use Backend\Libs\JWTWrapper;
use Backend\Libs\Util;
use Backend\Model\User;

class AuthController
{
    public function __construct()
    {
        date_default_timezone_set("America/Sao_Paulo");
        
        $_POST = json_decode(file_get_contents('php://input'), true);

        $email = (isset($_POST['email'])) ? $_POST['email'] : false;
        $password = (isset($_POST['password'])) ? $_POST['password'] : false;

        
        if(!$email){
            Util::returnJson(401, true, "Usuário não informado");
        }

        if(!$password){
            Util::returnJson(401, true, "Senha não informada");
        }

        $user = (new User())->getUserByEmailAndPassword($email, $password);

        if(!$user){
            Util::returnJson(401, true, "Usuário e/ou senha incorretos");
        }

        $today = date("d-m-Y H:i:s");
        
        $jwt = array(
            'createdAt' => strtotime($today),
            'expiration' => strtotime("+24 hours", strtotime($today)),
            'userData' => [
                'id' => $user->id,
                'name' => $user->email,
                'password' => $user->password
            ]
        );

        Util::returnJson(200, false, "Token gerado com sucesso", json_encode(array("token" => JWTWrapper::encode($jwt), "id" => $user->id, "email" => $user->email, "name" => $user->name)));
    }
}