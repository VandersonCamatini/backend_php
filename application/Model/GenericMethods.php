<?php 

namespace Backend\Model;

use Backend\Core\Model;

class GenericMethods extends Model
{
    public function getAll($table){
        $sql = "SELECT
                    *
                FROM
                    {$table}
                ";
        $query = $this->db->prepare($sql);
        $query->execute();

        return $query->fetchAll();
    }

    public function insert($arrayPost, $table, $return = null, $lowcase = false)
    {

        foreach ($arrayPost as $key => $value) {
            $arrColumn[] = $key;
            $arrColumnPdo[] = ":" . $key;

            if ($key == 'password') {
                $arrValue[':' . $key] = $value;
            } else {
                $arrValue[':' . $key] = (!$lowcase) ? mb_strtoupper($value, 'UTF-8') : $value;
            }
        }

        $column = implode(",", $arrColumn);
        $pdo = implode(",", $arrColumnPdo);

        $sql = "INSERT INTO {$table} ({$column}) VALUES ({$pdo})";

        $query = $this->db->prepare($sql);
        $parameters = $arrValue;

        $query->execute($parameters);

        if ($return) {
            $lastId = $this->db->lastInsertId();
            return $lastId;
        }
    }

    public function update($arrayPost, $table, $where_col, $where_val, $lowcase = false)
    {
        foreach ($arrayPost as $key => $value) {
            $arrColumn[] = $key . " = " . ":" . $key;

            if ($key == 'password') {
                $arrValue[':' . $key] = $value;
            } else {
                $arrValue[':' . $key] = (!$lowcase) ? mb_strtoupper($value, 'UTF-8') : $value;
            }
        }

        $column = implode(",", $arrColumn);

        $sql = "UPDATE {$table} SET {$column} WHERE {$where_col} = :id";
        $query = $this->db->prepare($sql);
        $parameters = $arrValue;
        $parameters[':id'] = $where_val;

        $query->execute($parameters);
    }

    public function delete($table, $where_col, $where_val)
    {
        $sql = "DELETE FROM {$table} WHERE {$where_col} = :id";
        $query = $this->db->prepare($sql);
        $parameters[':id'] = $where_val;

        $query->execute($parameters);
    }
}

