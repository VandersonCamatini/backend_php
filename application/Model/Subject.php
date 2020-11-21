<?php

namespace Backend\Model;

use Backend\Core\Model;

class Subject extends Model
{
    public function getSubjectById($subjectId)
    {
        $sql = "SELECT
                    *
                FROM
                    subjects
                WHERE
                    id = :id";

        $query = $this->db->prepare($sql);
        $parameters = array(":id" => $subjectId);
        $query->execute($parameters);

        return $query->fetch();
    }

    public function getSubjectsByIdUser($userId)
    {
        $sql = "SELECT
                    *
                FROM
                    subjects
                WHERE
                    id_user = :id_user";

        $query = $this->db->prepare($sql);
        $parameters = array(":id_user" => $userId);
        $query->execute($parameters);

        return $query->fetchAll();
    }
}