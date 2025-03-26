<?php
    // Headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json'); 
    header('Access-Control-Allow-Methods: PUT'); 
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

    $author->author = $data->author;

    if (isset($author->id, $author->author)) {
        if ($author->test_exists()) {
            // Update author
            if($author->update()) {
                echo json_encode(
                    array('message' => 'author Updated')
                );
            } else {       
                echo json_encode(
                    array('message' => 'author Not Updated')
                );
            }
        }
    } else {
        echo json_encode(
            array('message' => 'Missing Required Parameters')
        );
    }