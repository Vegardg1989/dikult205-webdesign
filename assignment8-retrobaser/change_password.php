<?php /* Done by Student 110 */

/* =======DEBUGING========*/

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$page_title = 'Change Your Password';
include('templates/header.php');

$message = '';
$succesmessage = '' ;

// If no user_id session variable exists, redirect the user:
if (!isset($_SESSION['user_id'])) {

	$url = 'https://dikult205.k.uib.no/2020V/groups/gruppe3/assignment8/index.php'; // Define the URL.
	ob_end_clean(); // Delete the buffer.
	header("Location: $url");
	exit(); // Quit the script.

}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	require('mysqli_connect.php');

	// Check for a new password and match against the confirmed password:
	$p = FALSE;
	if (strlen($_POST['password']) >= 8) {
		if ($_POST['password'] == $_POST['password_repeat']) {
			$p = password_hash($_POST['password'], PASSWORD_DEFAULT);
		} else {
			$message .= '<p>Your password did not match the confirmed password!</p>';
		}
	} else {
		$message .= '<p>Please enter a valid password!</p>';
	}

	if ($p) { // If everything's OK.

		// Make the query:
		$q = "UPDATE users SET pass='$p' WHERE user_id={$_SESSION['user_id']} LIMIT 1";
		$r = mysqli_query($conn, $q) or trigger_error("Query: $q\n<br>MySQL Error: " . mysqli_error($conn));
		if (mysqli_affected_rows($conn) == 1) { // If it ran OK.
			$succesmessage .= 'Your password has been changed';
			mysqli_close($conn); // Close the database connection.

		} else { // If it did not run OK.

			$message .= '<p>Your password was not changed. Make sure your new password is different than the current password. Contact the system administrator if you think an error occurred.</p>';

		}

	} else { // Failed the validation test.
		$message .= '<p>Please try again.</p>';
	}

	mysqli_close($conn); // Close the database connection.

} // End of the main Submit conditional.
?>
<div class="forms-container">

<section class="forms-section">

<form action="change_password.php" method="post">
<?php 
echo $message;
echo $succesmessage;
echo '<br>';

?>
	<label for="password">New Password:</label>
	<input type="password" name="password">
	<label for="password">Repeat Password:</label>
	<input type="password" name="password_repeat">
	
	<input type="submit" name="submit" value= "Submit Password" class="SubmitNewPassword-Button">
</div>
</form>
</section>
</div>
<?php include('templates/footer.php'); ?>


