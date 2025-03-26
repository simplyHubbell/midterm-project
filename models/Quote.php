<?php
    class Quote {
        // DB stuff
        private $conn;
        private $table = 'quotes';

        // Post Properties
        public $id;
        public $quote;
        public $author_id;
        public $author_name;
        public $category_id;
        public $category_name;

        // Constructor with DB
        public function __construct($db) {
            $this->conn = $db;
        }

        // Get Quotes
        public function read($filter) {
            // Create query
            $query = 'SELECT 
                    c.category as category_name,
                    a.author as author_name,
                    q.id,
                    q.quote,
                    q.author_id,
                    q.category_id
                FROM
                    ' . $this->table . ' q
                LEFT JOIN
                    authors a ON q.author_id = a.id
                LEFT JOIN
                    categories c ON q.category_id = c.id
                    ' . $filter . '
                GROUP BY
                    q.id' ;

            // Prepare statement
            $stmt = $this->conn->prepare($query);

            // Execute query
            $stmt->execute();

            return $stmt;
        }

        // Get Single Quote
        public function read_single() {
            // Create query

            $query = 'SELECT 
                    c.category as category_name,
                    a.author as author_name,
                    q.id,
                    q.quote,
                    q.author_id,
                    q.category_id
                FROM
                    ' . $this->table . ' q
                LEFT JOIN
                    authors a ON q.author_id = a.id
                LEFT JOIN
                    categories c ON q.category_id = c.id
                WHERE
                    q.id = ?
                LIMIT
                    0,1';

            // Prepare statement
            $stmt = $this->conn->prepare($query);

            // Bind ID
            $stmt->bindParam(1, $this->id);

            // Execute query
            $stmt->execute();

            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            if (isset($row['quote'])) {

                // Set properties
                $this->quote = $row['quote'];
                $this->author_id = $row['author_id'];
                $this->author_name = $row['author_name'];
                $this->category_id = $row['category_id'];
                $this->category_name = $row['category_name'];

            }             
        }

        // Create Quote
        public function create() {
            // Create query
            $query = 'INSERT INTO ' . $this->table . ' (quote, author_id, category_id)
                    VALUES (:quote, :author_id, :category_id)';

            // Prepare statement
            $stmt = $this->conn->prepare($query);

            // Clean data
            $this->quote = htmlspecialchars(strip_tags($this->quote));
            $this->author_id = htmlspecialchars(strip_tags($this->author_id));
            $this->category_id = htmlspecialchars(strip_tags($this->category_id));

            // Bind data
            $stmt->bindParam(':quote', $this->quote);
            $stmt->bindParam(':author_id', $this->author_id);
            $stmt->bindParam(':category_id', $this->category_id);

            // Execute query
            if($stmt->execute()) {
                return true;
            } 

            // print error if something goes wrong
            printf("Error: %s.\n", $stmt->error);

            return false;

            $row = $stmt->fetch(PDO::FETCH_ASSOC);
        }

        // Update Post
        public function update() {
            // Create query
            $query = 'UPDATE ' . $this->table . '
                SET
                    quote = :quote,
                    author_id = :author_id,
                    category_id = :category_id
                WHERE
                    id = :id';

            // Prepare statement
            $stmt = $this->conn->prepare($query);

            // Clean data
            $this->quote = htmlspecialchars(strip_tags($this->quote));
            $this->author_id = htmlspecialchars(strip_tags($this->author_id));
            $this->category_id = htmlspecialchars(strip_tags($this->category_id));
            $this->id = htmlspecialchars(strip_tags($this->id));

            // Bind data
            $stmt->bindParam(':quote', $this->quote);
            $stmt->bindParam(':author_id', $this->author_id);
            $stmt->bindParam(':category_id', $this->category_id);
            $stmt->bindParam(':id', $this->id);

            // Execute query
            if($stmt->execute()) {
                return true;
            } 

                // print error if something goes wrong
            printf("Error: %s.\n", $stmt->error);

            return false;            
        }

        // Delete Quote
        public function delete() {
            // Create query
            $query = 'DELETE FROM ' . $this->table . ' WHERE id = :id';

            // Prepare statement
            $stmt = $this->conn->prepare($query);

            // Clean data
            $this->id = htmlspecialchars(strip_tags($this->id));

            // Bind data
            $stmt->bindParam(':id', $this->id);

            // Execute query
            if($stmt->execute()) {
                return true;
            } 

            // print error if something goes wrong
            printf("Error: %s.\n", $stmt->error);

            return false;
        }

        public function test_exists() {

            if (isset($this->author_id)) {
                // Create query
                $query = 'SELECT * FROM authors WHERE id = ' . $this->author_id;

                // Prepare statement
                $stmt = $this->conn->prepare($query);

                // Execute query
                $stmt->execute();

                $num = $stmt->rowCount();

                // Check if any posts
                if($num > 0) {} else {
                    // No posts
                    echo json_encode(
                        array('message' => 'author_id Not Found')
                    );

                    return false;
                } 
            }

            if (isset($this->category_id)) {

                // Create query
                $query = 'SELECT * FROM categories WHERE id = ' . $this->category_id;

                // Prepare statement
                $stmt = $this->conn->prepare($query);

                // Execute query
                $stmt->execute();

                $num = $stmt->rowCount();

                // Check if any posts
                if($num > 0) {} else {
                    // No posts
                    echo json_encode(
                        array('message' => 'category_id Not Found')
                    );

                    return false;
                } 
            }

            if (isset($this->id)) {
                // Create query
                $query = 'SELECT * FROM quotes WHERE id = ' . $this->id;

                // Prepare statement
                $stmt = $this->conn->prepare($query);

                // Execute query
                $stmt->execute();

                $num = $stmt->rowCount();

                // Check if any posts
                if($num > 0) {} else {
                    // No posts
                    echo json_encode(
                        array('message' => 'No Quotes Found')
                    );

                    return false;
                } 
            }
                
            return true;
        }
    }