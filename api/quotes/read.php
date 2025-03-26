<?php
    // Headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');

    include_once '../../config/Database.php';
    include_once '../../models/Quote.php';

    // Instantiate DB & connect
    $database = new Database();
    $db = $database->connect();

    // Instantiate blog post object
    $quote = new Quote($db);

    $filter = '';

    if (isset($_GET['author_id']) AND isset($_GET['category_id'])) {
        $filter .= 'WHERE
                    q.author_id = ' . $_GET['author_id'] . '
                    AND
                    q.category_id = ' . $_GET['category_id'];
    } else if (isset($_GET['author_id']) OR isset($_GET['category_id'])) {

        if (isset($_GET['author_id'])) {
            $filter .= 'WHERE
                        q.author_id = ' . $_GET['author_id'];
        } 
        
        if (isset($_GET['category_id'])) {            
            $filter .= 'WHERE
                        q.category_id = ' . $_GET['category_id'];
        } 
    }
   

    // Blog post query
    $result = $quote->read($filter);
    // Get row count
    $num = $result->rowCount();

    // Check if any posts
    if($num > 0) {
        // Post array
        $quotes_arr = array();
        $quotes_arr['data'] = array();

        while($row = $result->fetch(PDO::FETCH_ASSOC)) {
            extract($row);

            $quote_item = array(
                'id' => $id,
                'quote' => html_entity_decode($quote),
                'author_id' => $author_id,
                'author_name' => $author_name,
                'category_id' => $category_id,
                'category_name' => $category_name
            );

            // Push to "data"
            array_push($quotes_arr['data'], $quote_item);
        }

        // Turn to JSON & output
        echo json_encode($quotes_arr);

    } else {
        // No posts
        echo json_encode(
            array('message' => 'No Quotes Found')
        );
    }