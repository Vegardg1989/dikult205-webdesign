<?php /* Done by Student 110 */
	session_start();
	session_unset();
	session_destroy(); // Destroy the session itself.
	setcookie(session_name(), '', time()-3600); // Destroy the cookie.
	header("Location: index.php");

