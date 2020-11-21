<?php

namespace Backend\Model;

use Backend\Core\Model;

class User extends Model
{
    public function getUserByEmailAndPassword($email, $password)
    {
        $password = md5($password);

        $sql = "SELECT 
                    *
                FROM 
                    users 
                WHERE
                    UPPER(email) = UPPER(:email)
                AND
                    password = :password";

        $query = $this->db->prepare($sql);
        $parameters = array(":email" => $email, ":password" => $password);
        $query->execute($parameters);

        return $query->fetch();
    }

    public function getUserByEmail($email)
    {

        $sql = "SELECT 
                    *
                FROM 
                    users 
                WHERE
                    UPPER(email) = UPPER(:email)";

        $query = $this->db->prepare($sql);
        $parameters = array(":email" => $email);
        $query->execute($parameters);

        return $query->fetch();
    }

    public function getUserByEmailWithDifferentId($email, $userId)
    {
        $sql = "SELECT 
                    *
                FROM 
                    users 
                WHERE
                    UPPER(email) = UPPER(:email)
                AND
                    id != :id";

        $query = $this->db->prepare($sql);
        $parameters = array(":email" => $email, ":id" => $userId);
        $query->execute($parameters);

        return $query->fetch();
    }
    
    public function getUserById($userId)
    {
        $sql = "SELECT
                    *
                FROM
                    users
                WHERE
                    id = :id";

        $query = $this->db->prepare($sql);
        $parameters = array(":id" => $userId);
        $query->execute($parameters);

        return $query->fetch();
    }
}