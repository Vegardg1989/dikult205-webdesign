<?php /* Done by both Student 108 and 110 */

// Database access information.
// Copy this file to mysqli_connect.php and fill in your own values.
// The real mysqli_connect.php is kept out of git (.gitignore).

$dbuser = 'your_db_user';
$dbpwd  = 'your_db_password';
$dbhost = 'localhost';
$dbname = 'your_db_name';

// Make the connection to retrobaser
$conn = mysqli_connect($dbhost, $dbuser, $dbpwd, $dbname);

// If no connection could be made, trigger an error:
if (!$conn) {
	die('Connection to my MYSQL failed ' . mysqli_connect_error());
} else { // Set charset
	mysqli_set_charset($conn, 'utf8');
}
