<?php
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    $method = $_SERVER['REQUEST_METHOD'];


    if ($method === 'GET') {
        header('Access-Control-Allow-Methods: GET');
        header('Access-Control-Allow-Headers: Origin, Accept, Content-Type, X-Requested-With');
        
       if (isset($_GET['id'])) {
        require 'read_single.php';
       } else {
        require 'read.php';
        }
        exit();
    }

    if ($method === 'POST') {
        header('Access-Control-Allow-Methods: POST');
        header('Access-Control-Allow-Headers: Origin, Accept, Content-Type, X-Requested-With');
        
        require 'create.php';
        exit();
    }

    if ($method === 'PUT') {
        header('Access-Control-Allow-Methods: PUT');
        header('Access-Control-Allow-Headers: Origin, Accept, Content-Type, X-Requested-With');
        
        require 'update.php';
        exit();
    }

    if ($method === 'DELETE') {
        header('Access-Control-Allow-Methods: DELETE');
        header('Access-Control-Allow-Headers: Origin, Accept, Content-Type, X-Requested-With');
        
        require 'delete.php';
        exit();
    }