<?php

namespace Backend\Model;

use Backend\Core\Model;

class Memory extends Model
{
    public function getMemoryById($memoryId)
    {
        $sql = "SELECT
                    moc.*, sbs.name AS subject_name, sbs.teacher_name
                FROM
                    memories_of_class moc
                LEFT JOIN
                    subjects sbs
                ON
                    sbs.id = moc.id_subject
                WHERE
                    moc.id = :id";

        $query = $this->db->prepare($sql);
        $parameters = array(":id" => $memoryId);
        $query->execute($parameters);

        return $query->fetch();
    }

    public function getAllInterationsByIdMemory($memoryId)
    {
        $sql = "SELECT
                    interation
                FROM
                    interations_of_user
                WHERE
                    id_memory = :id_memory";

        $query = $this->db->prepare($sql);
        $parameters = array(":id_memory" => $memoryId);
        $query->execute($parameters);

        return $query->fetchAll();
    }

    public function deleteAllInterarionByMemoryId($memoryId)
    {
        $sql = "DELETE
                FROM
                    interations_of_user
                WHERE
                    id_memory = :id";

        $query = $this->db->prepare($sql);
        $parameters = array(":id" => $memoryId);
        $query->execute($parameters);
    }

    public function getMemoriesByIdUser($userId)
    {
        $sql = "SELECT
                    moc.*, sbs.name AS subject_name, sbs.teacher_name
                FROM
                    memories_of_class moc
                LEFT JOIN
                    subjects sbs
                ON
                    sbs.id = moc.id_subject
                WHERE
                    moc.id_user = :id_user";

        $query = $this->db->prepare($sql);
        $parameters = array(":id_user" => $userId);
        $query->execute($parameters);

        return $query->fetchAll();
    }
}