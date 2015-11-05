<?php

namespace App\models;

use Framework\DatabaseModel;

class Sth extends DatabaseModel
{
    public function selectAllData()
    {
        $stmt = $this->db->query('SELECT * FROM sth');
        return $stmt->fetchAll();
    }
}