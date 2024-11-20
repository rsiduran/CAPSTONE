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
    'admin' => ['index.php','missing.php','wandering.php','found.php','view_ProfileFound.php','view_ProfileMissing.php','view_ProfileWandering.php',],
    'staff' => ['index.php','addPetAdoption.php','rescuePending.php','rescueReviewing.php','rescueOngoing.php','rescueRescued.php','rescueDeclined.php'
    ,'view_profileDeclined.php','view_profilePending.php','view_profileReviewing.php','view_profileRescued.php','view_profileOngoing.php'
    ,'applicationApproved.php','applicationCompleted.php','applicationPending.php','applicationRejected.php','applicationReviewing.php'
    ,'view_applicationApproved.php','view_applicationCompleted.php','view_applicationPending.php','view_applicationRejected.php','view_applicationReviewing.php']
];

// Get current page name
$current_page = basename($_SERVER['PHP_SELF']);

// Check if the user is accessing an allowed page
if (!in_array($current_page, $allowed_pages[$_SESSION['user_type']])) {
    // Redirect user to index.php if not allowed on this page
    header("Location: index.php");
    exit;
}
?>
