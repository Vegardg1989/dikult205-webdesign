<?php /* Done by Student 110 */ 
# Script 18.9 - logout.php
// This is the logout page for the site.
require('config.inc.php');


// If no first_name session variable exists, redirect the user:
if (!isset($_SESSION['user_id'])) {

	$url = BASE_URL . 'index.php'; // Define the URL.
	ob_end_clean(); // Delete the buffer.
	header("Location: $url");
	exit(); // Quit the script.

} else { // Log out the user.

	$_SESSION = []; // Destroy the variables.
	session_destroy(); // Destroy the session itself.
	setcookie(session_name(), '', time()-3600); // Destroy the cookie.

}
?>
