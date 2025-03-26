<?php
    class Author {
        // DB stuff
        private $conn;
        private $table = 'authors';

        // author Properties
        public $id;
        public $author;

        // Constructor with DB
        public function __construct($db) {
            $this->conn = $db;
        }

        // Get Authors
        public function read() {
            // Create query
            $query = 'SELECT 
                    id,
                    author
                FROM
                    ' . $this->table;

            // Prepare statement
            $stmt = $this->conn->prepare($query);

            // Execute query
            $stmt->execute();

            return $stmt;
        }

        // Get Single Author
        public function read_single() {
            // Create query
            $query = 'SELECT 
                    id,
                    author
                FROM
                    ' . $this->table . ' 
                WHERE
                    id = ?
                LIMIT 0,1';

            // Prepare statement
            $stmt = $this->conn->prepare($query);

            // Bind ID
            $stmt->bindParam(1, $this->id);

            // Execute query
            $stmt->execute();

            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            if (isset($row['id'])) {            
                 // Set properties
                $this->id = $row['id'];
                $this->author = $row['author'];
            } else {
                echo json_encode(
                    array('message' => 'author_id Not Found')
                );
            }
        }

        // Create author
        public function create() {
            // Create query
            $query = 'INSERT INTO ' . $this->table . ' (author)
                    VALUES (:author)';

            // Prepare statement
            $stmt = $this->conn->prepare($query);

            // Clean data
            $this->author = htmlspecialchars(strip_tags($this->author));

            // Bind data
            $stmt->bindParam(':author', $this->author);

            // Execute query
            if($stmt->execute()) {
                return true;
            } 

            // print error if something goes wrong
            printf("Error: %s.\n", $stmt->error);

            return false;
        }

        // Update author
        public function update() {
            // Create query
            $query = 'UPDATE ' . $this->table . '
                SET
                    author = :author
                WHERE
                    id = :id';

            // Prepare statement
            $stmt = $this->conn->prepare($query);

            // Clean data
            $this->author = htmlspecialchars(strip_tags($this->author));
            $this->id = htmlspecialchars(strip_tags($this->id));

            // Bind data
            $stmt->bindParam(':author', $this->author);
            $stmt->bindParam(':id', $this->id);

            // Execute query
            if($stmt->execute()) {
                return true;
            } 

            // print error if something goes wrong
            printf("Error: %s.\n", $stmt->error);

            return false;
        }        

        // Delete author
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

            if (isset($this->id)) {
                // Create query
                $query = 'SELECT * FROM authors WHERE id = ' . $this->id;

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
                
            return true;
        }
    }