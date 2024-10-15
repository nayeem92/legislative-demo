<?php

class UserRepository {
    private $connection;

    public function __construct($connection) {
        $this->connection = $connection;
    }

    // Method to get a user by username
    public function getUserByUsername($username) {
        $query = "SELECT * FROM users WHERE username = ? LIMIT 1";
        $stmt = $this->connection->prepare($query);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->fetch_assoc(); // Return the user data or null if not found
    }

    // Method to authenticate a user
    public function authenticate($username, $password) {
        $user = $this->getUserByUsername($username); // Fetch user details

        // Check if user exists and compare passwords
        if ($user && $user['password_hash'] === $password) { // Check password against password_hash
            return new User(
                $user['user_id'],
                $user['username'],
                $user['password_hash'], // Use password_hash from the database
                $user['role'],
                $user['email']
            );
        }

        return null; // Return null if authentication fails
    }
}
?>
