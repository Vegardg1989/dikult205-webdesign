<?php /* Done by Student 110 */
# Activation of user account 

$page_title = 'Activate Your Account';
include('templates/header.php');

// If $a and $b don't exist or aren't of the proper format, redirect the user:
if (isset($_GET['a'], $_GET['b'])
	&& filter_var($_GET['a'], FILTER_VALIDATE_EMAIL)
	&& (strlen($_GET['b']) == 32 )
	) {

	// Update the database...
	require('mysqli_connect.php');
	$query = "UPDATE users SET active=NULL WHERE (email='" . mysqli_real_escape_string($conn, $_GET['a']) . "' AND active='" . mysqli_real_escape_string($conn, $_GET['b']) . "') LIMIT 1";
	$result = mysqli_query($conn, $query) or trigger_error("Query: $query\n<br>MySQL Error: " . mysqli_error($conn));

	// Print a customized message:
	if (mysqli_affected_rows($conn) == 1) {
		echo "<h3>Your account is now active. You may now log in.</h3>";
	} else {
		echo '<p>Your account could not be activated. Please re-check the link or contact the system administrator.</p>';
	}

	mysqli_close($conn);

} else { // Redirect.

	$url = 'https://dikult205.k.uib.no/2020V/groups/gruppe3/assignment8/index.php'; // Define the URL.
	ob_end_clean(); // Delete the buffer.
	header("Location: $url");
	exit();

} // End of main IF-ELSE.

include('templates/footer.php');
?>
