<?php
require_once 'config.php';

// destroy all session data
session_unset();
session_destroy();

// redirect to the login page
header('Location: index.php');
exit();

?>