<?php

namespace Backend\Controller;

use Backend\Libs\Util;
use Backend\Model\GenericMethods;
use Backend\Model\User;

class UsersController
{   
    private $table = "users";

    public function __construct()
    {
        $headers = getallheaders();
        $token = (isset($headers['token-security'])) ? $headers['token-security'] : false;
        
        Util::protect($token);
    }
    
    public function getAllUsers(){
        try {
            $users = (new GenericMethods())->getAll($this->table);

            Util::returnJson(200,  false, "", $users);
        } catch (\PDOException $ex) {
            Util::returnJson(500, true, $ex->message);
        }
    }

    public function createUser(){
        if(isset($_POST)){
            try {
                foreach($_POST AS $key => $field){
                    if(empty($field)){
                        Util::returnJson(400, true, "O campo ". $key  ." não pode ser vazio.");
                    }

                    if($key === 'email'){
                        $return = (new User())->getUserByEmail($field);
                        if($return){
                            Util::returnJson(400, true, "E-mail inválido, usuário já existente.");
                        }
                    }
                }
           
                $_POST['password'] = md5($_POST['password']);
                $userId = (new GenericMethods())->insert($_POST, $this->table , true);
                $newUser = (new User())->getUserById($userId);

                Util::returnJson(201,  false, "Usuário criado com sucesso.", $newUser);
            } catch (\PDOException $ex) {
                Util::returnJson(500, true, $ex->message);
            }
        }else{
            Util::returnJson(400, true, "Os campos não estão preenchidos corretamente.");
        }
    }

    public function getUser($userId){
        if($userId){
            try {
                $user = (new User())->getUserById($userId);

                if($user){
                    Util::returnJson(200,  false, "Usuário encontrado.", $user);
                }else{
                    Util::returnJson(404,  true, "Usuário não encontrado.");
                }
                

            } catch (\PDOException $ex) {
                Util::returnJson(500, true, $ex->message);
            }
        }else{
            Util::returnJson(400, true, "O id do usuário não foi informado.");
        }
    }

    public function updateUser($userId){
        if($userId){
            if(isset($_POST)){
                try {
                    foreach($_POST AS $key => $field){
                        if(empty($field)){
                            Util::returnJson(400, true, "O campo ". $key  ." não pode ser vazio.");
                        }
    
                        if($key === 'email'){
                            $return = (new User())->getUserByEmailWithDifferentId($field, $userId);
                            if($return){
                                Util::returnJson(400, true, "E-mail inválido, usuário já existente.");
                            }
                        }
                    }
               
                    $user = (new User())->getUserById($userId);
    
                    if($user){
                        $_POST['password'] = md5($_POST['password']);
                        $_POST['updated_at'] = date('Y-m-d H:i:s');
                        (new GenericMethods())->update($_POST, $this->table, 'id', $userId);
                        $updatedUser = (new User())->getUserById($userId);
    
                        Util::returnJson(200,  false, "Usuário alterado com sucesso.", $updatedUser);
                    }else{
                        Util::returnJson(404,  true, "Usuário não encontrado.");
                    }
                    
    
                } catch (\PDOException $ex) {
                    Util::returnJson(500, true, $ex->message);
                }
            }else{
                Util::returnJson(400, true, "Os campos não estão preenchidos corretamente.");
            }
        }else{
            Util::returnJson(400, true, "O id do usuário não foi informado.");
        }
    }

    public function deleteUser($userId){
        if($userId){
            try {
                $user = (new User())->getUserById($userId);
    
                if($user){
                    (new GenericMethods())->delete($this->table, 'id', $userId);
    
                    Util::returnJson(200,  false, "Usuário deletado com sucesso.");
                }else{
                    Util::returnJson(404,  true, "Usuário não encontrado.");
                }
    
            } catch (\PDOException $ex) {
                Util::returnJson(500, true, $ex->message);
            }
        }else{
            Util::returnJson(400, true, "O id do usuário não foi informado.");
        }
    }

    
   
}