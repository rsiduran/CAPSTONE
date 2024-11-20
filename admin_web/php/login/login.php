<?php
session_start();
$firebase = include('../../config/firebase.php');

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];  

    // Get user type from Firestore based on username and password
    $userType = $firebase->getUserType($username, $password);

    // Check the user type and redirect accordingly
    if ($userType == 'superadmin' || $userType == 'admin' || $userType == 'staff') {
        // Store user type in the session
        $_SESSION['user_type'] = $userType;
        $_SESSION['username'] = $username;  // Optional: store the username if needed

        if ($userType == 'superadmin') {
            header("Location: ../../index.php");
        } elseif ($userType == 'admin') {
            header("Location: ../../index.php");
        } elseif ($userType == 'staff') {
            header("Location: ../../index.php");
        }
        exit;
    } else {
        // Store error message in session for use in the login form
        $_SESSION['login_error'] = "Invalid login or user type.";
        header("Location: ../../loginForm.php");
        exit;
    }
}
?>
