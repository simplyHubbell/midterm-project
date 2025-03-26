<?php
    // Headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json'); 
    header('Access-Control-Allow-Methods: DELETE'); 
    header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With'); 

    include_once '../../config/Database.php';
    include_once '../../models/Quote.php';

    // Instantiate DB & connect
    $database = new Database();
    $db = $database->connect();

    // Instantiate blog post object
    $quote = new Quote($db);

    // Get raw posted data
    $data = json_decode(file_get_contents("php://input"));

    // Set ID to update
    $quote->id = $data->id;

    if (isset($quote->id)) {        
        if ($quote->test_exists()) {
            //Delete post
            if($quote->delete()) {
                echo json_encode(
                    array('Quote Deleted' => "$quote->id")
                );
            } else {       
                echo json_encode(
                    array('message' => 'Quote Not Deleted')
                );
            }
        }

    } else {
        echo json_encode(
            array('message' => 'Missing Required Parameters')
        );
    }