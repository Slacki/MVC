<?php

namespace Framework;

/**
 * Class DatabaseModel
 * Basicly same as \Framework\Model
 * Provides a way to access database object.
 *
 * @package Framework
 */
class DatabaseModel extends Model
{
    protected $db;

    public function __construct()
    {
        $this->db = App::$app->getDb();
    }
}