<?php
// Start the session
session_start();

// Unset all session variables
session_unset();

// Destroy the session
session_destroy();

// Redirect to the admin login page
header("Location: admin_login.php");
exit();
?>
