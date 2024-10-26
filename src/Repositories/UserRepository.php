<?php

namespace Repositories;

require_once __DIR__ . '/../Models/User.php';

use Models\User;

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

        // Debugging: Check if user is fetched correctly
        if ($user) {
            echo '<pre>'; print_r($user); echo '</pre>';
        }

        // Check if user exists and compare passwords using password_verify
        if ($user && password_verify($password, $user['password_hash'])) {
            return new User(
                $user['user_id'],
                $user['username'],
                $user['password_hash'],
                $user['role'],
                $user['email']
            );
        }

        return null; // Return null if authentication fails
    }

    // Method to create a new user
    public function createUser($username, $password, $role, $email) {
        $passwordHash = password_hash($password, PASSWORD_BCRYPT); // Hash the password
        $query = "INSERT INTO users (username, password_hash, role, email) VALUES (?, ?, ?, ?)";
        $stmt = $this->connection->prepare($query);
        $stmt->bind_param("ssss", $username, $passwordHash, $role, $email);

        return $stmt->execute();
    }

    // Additional helper methods can go here
}
