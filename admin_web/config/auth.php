<?php
session_start();

// Check if the user is logged in and has the correct user type
if (!isset($_SESSION['user_type'])) {
    // Redirect to login page if not logged in
    header("Location: loginForm.php");
    exit;
}

// Allow superadmin access to all pages
if ($_SESSION['user_type'] === 'superadmin') {
    return; // Skip access check for superadmin
}

// Define allowed pages for each user type
$allowed_pages = [
    'admin' => ['admin.php', 'missing_admin.php', 'found_admin.php', 'historyFoundAdmin.php', 'historyMissingAdmin.php' ],
    'staff' => ['supremo.php']
];

// Get current page name
$current_page = basename($_SERVER['PHP_SELF']);

// Check if the user is accessing an allowed page
if (!in_array($current_page, $allowed_pages[$_SESSION['user_type']])) {
    // Redirect user to their respective dashboard if not allowed on this page
    $redirect_pages = [
        'admin' => 'admin.php',
        'staff' => 'supremo.php'
    ];
    header("Location: " . $redirect_pages[$_SESSION['user_type']]);
    exit;
}
?>
