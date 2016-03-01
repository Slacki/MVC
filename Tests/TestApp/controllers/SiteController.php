<?php

namespace TestApp\controllers;

use Framework\Controller;

class SiteController extends Controller
{
    public function actionIndex()
    {
        echo 'Inside \TestApp\SiteController::actionIndex()';
    }

    public function actionView($id)
    {
        echo $id;
    }

    public function actionDelete($id, $param1 = 'xxx')
    {

    }
}