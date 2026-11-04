<?php /* Restored in 2026 by Vegard Gundersen with help from Claude (Anthropic's AI assistant).
 * The 2020 server code was lost; only the April 2020 rough draft survived. This file rebuilds the
 * submitted version from the June 2020 portfolio screenshots and reuses the Retrobaser login system. */

// Database access information.
// Copy this file to mysqli_connect.php and fill in your own values (kept out of git).
$dbuser = 'your_db_user';
$dbpwd  = 'your_db_password';
$dbhost = 'localhost';
$dbname = 'your_db_name';

$conn = mysqli_connect($dbhost, $dbuser, $dbpwd, $dbname);
if (!$conn) {
	die('Connection to MySQL failed: ' . mysqli_connect_error());
}
mysqli_set_charset($conn, 'utf8mb4');
