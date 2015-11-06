<?php

namespace App\controllers;

use Framework\Controller;
use Framework\View;

class SiteController extends Controller
{
    public function actionIndex()
    {
        $view = new View();
        $view->render('site/index', false);
    }
}