<?php

namespace Tests;

class DatabaseModelTest extends \PHPUnit_Framework_TestCase
{
    public function testQuery()
    {
        $model = new \App\models\Sth();
        $result = $model->selectAllData(); // fetchAll() inside

        $this->assertTrue(is_array($result));
    }
}