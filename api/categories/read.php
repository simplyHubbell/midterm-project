<?php
    // Headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');

    include_once '../../config/Database.php';
    include_once '../../models/Category.php';

    // Instantiate DB & connect
    $database = new Database();
    $db = $database->connect();

    // Instantiate category object
    $category = new Category($db);

    // Category query
    $result = $category->read();
    // Get row count
    $num = $result->rowCount();

    // Check if any categories
    if($num > 0) {
        // Cat array
        $cat_arr = array();
        $cat_arr['data'] = array();

        while($row = $result->fetch(PDO::FETCH_ASSOC)) {
            extract($row);

            $cat_item = array(
                'id' => $id,
                'category' => $category
            );

            // Push to "data"
            array_push($cat_arr['data'], $cat_item);
        }

        // Turn to JSON & output
        echo json_encode($cat_arr);

    } else {
        // No categories
        echo json_encode(
            array('message' => 'No Categories Found')
        );
    }