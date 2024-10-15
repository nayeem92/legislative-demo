<?php
session_start(); // Start the session at the beginning

class AuthController {
    private $userRepository;

    public function __construct($userRepository) {
        $this->userRepository = $userRepository;
    }

    // Handle login process
    public function login($username, $password) {
        // Fetch the user by username from the repository
        $user = $this->userRepository->getUserByUsername($username);

        // Check if user exists and compare passwords
        if ($user && $password === $user['password']) { // Plain password comparison
            // Set session variables
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];

            // Redirect based on role
            if ($user['role'] === 'Administrator') {
                header("Location: dashboard.php");
                exit();
            } else {
                echo "Access denied: Only Administrators can log in.";
                return false;
            }
        } else {
            // Invalid login
            echo "Invalid username or password.";
            return false;
        }
    }

    // Handle logout process
    public function logout() {
        session_start();
        session_unset();
        session_destroy();
        header("Location: login.php");
        exit();
    }
}
?>
