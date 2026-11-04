<?php /* Done by student 110 */

/* =======DEBUGING========*/

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// This script allows a user who has forgot their password to restore it. 

$page_title = 'Forgot Your Password';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	require('../mysqli_connect.php');

	// Assume nothing:
	$uid = FALSE;
	// Validate the email address...
	if (!empty($_POST['email'])) {

		// Check for the existence of that email address...
		$q = 'SELECT user_id FROM users WHERE email="'.  mysqli_real_escape_string($conn, $_POST['email']) . '"';
		$r = mysqli_query($conn, $q) or trigger_error("Query: $q\n<br>MySQL Error: " . mysqli_error($conn));

		if (mysqli_num_rows($r) == 1) { // Retrieve the user ID:
			list($uid) = mysqli_fetch_array($r, MYSQLI_NUM);

		} else { // No database match made.
			header("location: ../forgot_password.php?error=emailnomatch");
			exit();
		}

	} else { // No email!
		header("location: ../forgot_password.php?error=emptyemail");
		exit();
	} // End of empty($_POST['email']) IF.

	if ($uid) { // If everything's OK.

		// Create a new, random password:
		$p = substr(md5(uniqid(rand(), true)), 3, 15);
		$ph = password_hash($p,PASSWORD_DEFAULT);

		// Update the database:
		$q = "UPDATE users SET pass='$ph' WHERE user_id=$uid LIMIT 1";
		$r = mysqli_query($conn, $q) or trigger_error("Query: $q\n<br>MySQL Error: " . mysqli_error($conn));

		if (mysqli_affected_rows($conn) == 1) { // If it ran OK

			// Send an email:
			$body = "Your password to log into Retrobaser has been temporarily changed to '$p'. Please log in using this password and this email address. Then you may change your password to something more familiar.";
			mail($_POST['email'], 'Your temporary password.', $body, 'From: admin@retrobaser.com');

			header("location: ../forgot_password.php?retrive=sucess");
			mysqli_close($conn);
			exit(); // Stop the script.

		} else { // If it did not run OK.
			header("location: ../forgot_password.php?error=system");
			exit();
		}

	} else { // Failed the validation test.
		header("location: ../forgot_password.php?error=failedvalidation");
		exit();
	}

}else {
		header("location: ../index.php");
		exit();
} 
