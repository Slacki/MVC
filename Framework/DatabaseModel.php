<?php

namespace Framework;

class DatabaseModel extends Model
{
    protected $db;

    public function __construct()
    {
        $this->db = App::$app->getDb();
    }
}