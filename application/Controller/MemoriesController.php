<?php

namespace Backend\Controller;

use Backend\Libs\Util;
use Backend\Model\GenericMethods;
use Backend\Model\Memory;
use Backend\Model\Subject;
use Backend\Model\User;

class MemoriesController
{   
    private $table = "memories_of_class";

    public function __construct()
    {
        $headers = getallheaders();
        $token = (isset($headers['token-security'])) ? $headers['token-security'] : false;
        
        Util::protect($token);
    }

    public function getAllMemories($userId){
        if($userId){
            try {
                $memories = (new Memory())->getMemoriesByIdUser($userId);
    
                Util::returnJson(200,  false, "", $memories);
            } catch (\PDOException $ex) {
                Util::returnJson(500, true, $ex->message);
            }
        }else{
            Util::returnJson(400, true, "O id do usuário não foi informado.");
        }
       
    }
    
    public function createMemory(){
        $_POST = json_decode(file_get_contents('php://input'), true);

        if(isset($_POST)){
            try {
                $notObrigatory = [
                    "interations",
                    "observations"
                ];

                foreach($_POST AS $key => $field){
                    if(!in_array($key, $notObrigatory)){ // Campos que não precisam ser validados.

                        if(empty($field)){
                            Util::returnJson(400, true, "O campo ". $key  ." não pode ser vazio.");
                        }
                        
                        if($key === 'id_user' || !isset($_POST['id_user'])){
                            $memory = (new User())->getUserById($field);
                            if(!$memory){
                                Util::returnJson(404,  true, "Usuário não encontrado.");
                            }
                        }
    
                        if($key === 'id_subject' || !isset($_POST['id_subject'])){
                            $memory = (new Subject())->getSubjectById($field);
                            if(!$memory){
                                Util::returnJson(404,  true, "Disciplina não encontrada.");
                            }
                        }
                    }
                }

                $arrayInterations = [];
                if(isset($_POST['interations'])){
                    $arrayInterations = $_POST['interations'];
                    unset($_POST['interations']);
                }

                if(isset($_POST['created_at'])){
                    $_POST['created_at'] = str_replace('/', '-', $_POST['created_at']);
                    $_POST['created_at'] = date('Y-m-d', strtotime($_POST['created_at']));
                }

                $memoryId = (new GenericMethods())->insert($_POST, 'memories_of_class', true);
                $newMemory = (new Memory())->getMemoryById($memoryId);

                if(!empty($arrayInterations)){
                    foreach($arrayInterations AS $interation){ //Inserir cada interação daquela memória de aula
                        $insertInteration['id_memory'] = $memoryId;
                        $insertInteration['interation'] = $interation;

                        (new GenericMethods())->insert($insertInteration, 'interations_of_user');
                    }
                }

                Util::returnJson(201,  false, "Memória criada com sucesso.", $newMemory);
            } catch (\PDOException $ex) {
                Util::returnJson(500, true, $ex->message);
            }
        }else{
            Util::returnJson(400, true, "Os campos não estão preenchidos corretamente.");
        }
    }

    public function getMemory($memoryId){
        if($memoryId){
            try {
                $memory = (new Memory())->getMemoryById($memoryId);
                $interations = (new Memory())->getAllInterationsByIdMemory($memoryId);
                
                $memoryComplete = (Object)[
                    "memory" => $memory,
                    "interations" => $interations
                ];

                if($memory){
                    Util::returnJson(200,  false, "Memória encontrada.", $memoryComplete);
                }else{
                    Util::returnJson(404,  true, "Memória não encontrada.");
                }
            } catch (\PDOException $ex) {
                Util::returnJson(500, true, $ex->message);
            }
        }else{
            Util::returnJson(400, true, "O id da memória não foi informado.");
        }
    }

    public function updateMemory($memoryId){

        $_POST = json_decode(file_get_contents('php://input'), true);
        
        if($memoryId){
            if(isset($_POST)){
                try {
                    $notObrigatory = [
                        "interations",
                        "observations"
                    ];

                    foreach($_POST AS $key => $field){
                        if(!in_array($key, $notObrigatory)){ // Campos que não precisam ser validados.
                            if(empty($field)){
                                Util::returnJson(400, true, "O campo ". $key  ." não pode ser vazio.");
                            }

                            if($key === 'id_subject' || !isset($_POST['id_subject'])){
                                $memory = (new Subject())->getSubjectById($field);
                                if(!$memory){
                                    Util::returnJson(404,  true, "Memória não encontrada.");
                                }
                            }
                        }
                    }
               
                    $memory = (new Memory())->getMemoryById($memoryId);
    
                    if($memory){

                        $arrayInterations = [];
                        if(isset($_POST['interations'])){
                            $arrayInterations = $_POST['interations'];
                            unset($_POST['interations']);
                        }

                        $_POST['updated_at'] = date('Y-m-d H:i:s');
                        (new GenericMethods())->update($_POST, 'memories_of_class', 'id', $memoryId);
                        $updatedMemory = (new Memory())->getMemoryById($memoryId);

                        if(!empty($arrayInterations)){
                            (new Memory())->deleteAllInterarionByMemoryId($memoryId);

                            foreach($arrayInterations AS $interation){ //Inserir cada interação daquela memória de aula
                                $insertInteration['id_memory'] = $memoryId;
                                $insertInteration['interation'] = $interation;
        
                                (new GenericMethods())->insert($insertInteration, 'interations_of_user');
                            }
                        }
    
                        Util::returnJson(200,  false, "Memória alterada com sucesso.", $updatedMemory);
                    }else{
                        Util::returnJson(404,  true, "Memória não encontrada.");
                    }
                } catch (\PDOException $ex) {
                    Util::returnJson(500, true, $ex->message);
                }
            }else{
                Util::returnJson(400, true, "Os campos não estão preenchidos corretamente.");
            }
        }else{
            Util::returnJson(400, true, "O id da memória não foi informado.");
        }
    }

    public function deleteMemory($memoryId){
        if($memoryId){
            try {
                $memory = (new Memory())->getMemoryById($memoryId);
    
                if($memory){
                    (new GenericMethods())->delete('memories_of_class', 'id', $memoryId);
    
                    Util::returnJson(200,  false, "Memória deletada com sucesso.");
                }else{
                    Util::returnJson(404,  true, "Memória não encontrada.");
                }
    
            } catch (\PDOException $ex) {
                Util::returnJson(500, true, $ex->message);
            }
        }else{
            Util::returnJson(400, true, "O id da memória não foi informado.");
        }
    }
   
}