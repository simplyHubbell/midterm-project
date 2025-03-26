<?php
    class Category {
        // DB stuff
        private $conn;
        private $table = 'categories';

        // Category Properties
        public $id;
        public $category;

        // Constructor with DB
        public function __construct($db) {
            $this->conn = $db;
        }

        // Get Categories
        public function read() {
            // Create query
            $query = 'SELECT 
                    id,
                    category
                FROM
                    ' . $this->table;

            // Prepare statement
            $stmt = $this->conn->prepare($query);

            // Execute query
            $stmt->execute();

            return $stmt;
        }

        // Get Single Category
        public function read_single() {
            // Create query
            $query = 'SELECT 
                    id,
                    category
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
                $this->category = $row['category'];

            } else {
                echo json_encode(
                    array('message' => 'category_id Not Found')
                );
            }
        }

        // Create Category
        public function create() {
            // Create query
            $query = 'INSERT INTO ' . $this->table . ' (category)
                    VALUES (:category)';

            // Prepare statement
            $stmt = $this->conn->prepare($query);

            // Clean data
            $this->category = htmlspecialchars(strip_tags($this->category));

            // Bind data
            $stmt->bindParam(':category', $this->category);

            // Execute query
            if($stmt->execute()) {
                return true;
            } 

            // print error if something goes wrong
            printf("Error: %s.\n", $stmt->error);

            return false;
        }

        // Update Category
        public function update() {
            // Create query
            $query = 'UPDATE ' . $this->table . '
                SET
                    category = :category
                WHERE
                    id = :id';

            // Prepare statement
            $stmt = $this->conn->prepare($query);

            // Clean data
            $this->category = htmlspecialchars(strip_tags($this->category));
            $this->id = htmlspecialchars(strip_tags($this->id));

            // Bind data
            $stmt->bindParam(':category', $this->category);
            $stmt->bindParam(':id', $this->id);

            // Execute query
            if($stmt->execute()) {
                return true;
            } 

            // print error if something goes wrong
            printf("Error: %s.\n", $stmt->error);

            return false;
        }        

        // Delete Category
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
                $query = 'SELECT * FROM categories WHERE id = ' . $this->id;

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
                
            return true;
        }
    }