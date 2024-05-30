<?php

namespace App\Controllers;
class Controller{
    public function index(){
        include BASE_PATH . '/app/routes.php';
    }
}