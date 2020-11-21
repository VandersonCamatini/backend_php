<?php

namespace Backend\Libs;

use Exception;
use Backend\Model\User;

class Util
{
    /**
     * @param string $status HTTP Status Code
     * @param bool $error
     * @param string $message
     * @param mixed $return
     */
    public static function returnJson($status, $error, $message, $return = false)
    {
        header("HTTP/1.1 $status");
        header('Content-Type: application/json');

        echo json_encode(array('erro' => $error, 'mensagem' => $message, 'retorno' => $return));
        die();
    }

    /**
     * @param false|string $token
     */
    public static function protect($token = false)
    {
        if(!$token){
            self::returnJson(401, true, "Token nao informado");
        }

        try{
            date_default_timezone_set("America/Sao_Paulo");

            $token = JWTWrapper::decode($token);
            $today = strtotime(date("d-m-Y H:i:s"));
            $user = (new User())->getUserById($token->userData->id);

            if($token->expiration < $today || !$user){
                self::returnJson(401, true, "Token inválido");
            }
        }catch(Exception $ex){
            self::returnJson(401, true, "Token incorreto: {$ex->getMessage()}");
        }
    }
}