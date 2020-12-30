<?php

namespace Backend\Controller;

use Backend\Libs\Util;
use Backend\Model\GenericMethods;
use Backend\Model\Subject;
use Backend\Model\User;

class SubjectsController
{   
    private $table = "subjects";

    public function __construct()
    {
        $headers = getallheaders();
        $token = (isset($headers['token-security'])) ? $headers['token-security'] : false;
        
        Util::protect($token);
    }

    public function getAllSubjects($userId){
        if($userId){
            try {
                $subjects = (new Subject())->getSubjectsByIdUser($userId);
    
                Util::returnJson(200,  false, "", $subjects);
            } catch (\PDOException $ex) {
                Util::returnJson(500, true, $ex->message);
            }
        }else{
            Util::returnJson(400, true, "O id do usuário não foi informado.");
        }
       
    }
    
    public function createSubject(){

        $_POST = json_decode(file_get_contents('php://input'), true);

        if(isset($_POST)){
            try {
                foreach($_POST AS $key => $field){
                    if(empty($field)){
                        Util::returnJson(400, true, "O campo ". $key  ." não pode ser vazio.");
                    }
                    
                    if($key === 'id_user' || !isset($_POST['id_user'])){
                        $user = (new User())->getUserById($field);
                        if(!$user){
                            Util::returnJson(404,  true, "Usuário não encontrado.");
                        }
                    }
                }
           
                $subjectId = (new GenericMethods())->insert($_POST, $this->table, true);
                $newSubject = (new Subject())->getSubjectById($subjectId);

                Util::returnJson(201,  false, "Disciplina criada com sucesso.", $newSubject);
            } catch (\PDOException $ex) {
                Util::returnJson(500, true, $ex->message);
            }
        }else{
            Util::returnJson(400, true, "Os campos não estão preenchidos corretamente.");
        }
    }

    public function getSubject($subjectId){
        if($subjectId){
            try {
                $subject = (new Subject())->getSubjectById($subjectId);

                if($subject){
                    Util::returnJson(200,  false, "Disciplina encontrada.", $subject);
                }else{
                    Util::returnJson(404,  true, "Disciplina não encontrada.");
                }
            } catch (\PDOException $ex) {
                Util::returnJson(500, true, $ex->message);
            }
        }else{
            Util::returnJson(400, true, "O id da disciplina não foi informado.");
        }
    }

    public function updateSubject($subjectId){

        $_POST = json_decode(file_get_contents('php://input'), true);
      
        if($subjectId){
            if(isset($_POST)){
                try {
                    foreach($_POST AS $key => $field){
                        if($key !== 'updated_at'){
                            if(empty($field)){
                                Util::returnJson(400, true, "O campo ". $key  ." não pode ser vazio.");
                            }
                        }
                    }
               
                    $subject = (new Subject())->getSubjectById($subjectId);
    
                    if($subject){
                        $_POST['updated_at'] = date('Y-m-d H:i:s');
                        (new GenericMethods())->update($_POST, $this->table, 'id', $subjectId);
                        $updatedSubject = (new Subject())->getSubjectById($subjectId);
                        Util::returnJson(200,  false, "Disciplina alterada com sucesso.", $updatedSubject);
                    }else{
                        Util::returnJson(404,  true, "Disciplina não encontrada.");
                    }
                    
    
                } catch (\PDOException $ex) {
                    Util::returnJson(500, true, $ex->message);
                }
            }else{
                Util::returnJson(400, true, "Os campos não estão preenchidos corretamente.");
            }
        }else{
            Util::returnJson(400, true, "O id da disciplina não foi informado.");
        }
    }

    public function deleteSubject($subjectId){
        if($subjectId){
            try {
                $subject = (new Subject())->getSubjectById($subjectId);
    
                if($subject){
                    (new GenericMethods())->delete($this->table, 'id', $subjectId);
    
                    Util::returnJson(200,  false, "Disciplina deletada com sucesso.");
                }else{
                    Util::returnJson(404,  true, "Disciplina não encontrada.");
                }
    
            } catch (\PDOException $ex) {
                Util::returnJson(500, true, $ex->message);
            }
        }else{
            Util::returnJson(400, true, "O id da disciplina não foi informado.");
        }
    }

    
   
}