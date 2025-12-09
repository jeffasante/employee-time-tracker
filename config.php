<?php
// Database configuration
define( 'DB_HOST', 'localhost' );
define( 'DB_USER', 'root' );
define( 'DB_PASS', '' );
define( 'DB_NAME', 'time_tracker' );

// create database connection
try {
    $conn = new PDO( 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME,
    DB_USER, DB_PASS );

    $conn->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );

    // set default fetch mode to associative array
    $conn->setAttribute( PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC );

} catch ( PDOException $e ) {
    die( 'Connection failed: ' . $e->getMessage() );
}

// start session for authentication
session_start();

date_default_timezone_set('Africa/Accra');

// check if user is logged in
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

// helper functoin to require login
function requireLogin() {
    if (!isLoggedIn()) {
        header('Location: index.php');
        exit();
    }
}

?>