<?php
    // Headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json'); 
    header('Access-Control-Allow-Methods: DELETE'); 
    header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With'); 

    include_once '../../config/Database.php';
    include_once '../../models/Author.php';

    // Instantiate DB & connect
    $database = new Database();
    $db = $database->connect();

    // Instantiate author object
    $author = new author($db);

    // Get raw posted data
    $data = json_decode(file_get_contents("php://input"));

    // Set ID to update
    $author->id = $data->id;

    if (isset($author->id)) {

            //Delete post
        if($author->delete()) {
            echo json_encode(
                array('Author Deleted' => "$author->id")
            );
        } else {       
            echo json_encode(
                array('message' => 'author Not Deleted')
            );
        }

    } else {
            
        echo json_encode(
            array('message' => 'Missing Required Parameters')
        );
    }

    