<?php /* Done by Student 110 */

$page_title = 'Edit a User';
require 'templates/header.php';




	if ($_SESSION['user_level'] == 0) {
    
		$url = 'https://dikult205.k.uib.no/2020V/groups/gruppe3/assignment8/index.php'; // Define the URL.
		header("Location: $url");
		exit(); // Quit the script.   
	}

	
// Check for a valid user ID, through GET or POST:


if ( (isset($_GET['id'])) && (is_numeric($_GET['id'])) ) { // From view_users.php
	$id = $_GET['id'];
} elseif ( (isset($_POST['id'])) && (is_numeric($_POST['id'])) ) { // Form submission.
	$id = $_POST['id'];
} else { // No valid ID, kill the script.
header("Location:index.php");
	exit();
}

require('mysqli_connect.php');

echo'<div class="forms-container">';
echo'<section class="forms-section">';	

// Check if the form has been submitted:
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

	$errors = [];

	// Check for a first name:
	if (empty($_POST['username'])) {
		$errors[] = 'You forgot to enter a username.';
	} else {
		$username = mysqli_real_escape_string($conn, trim($_POST['username']));
	}

	// Check for an email address:
	if (empty($_POST['email'])) {
		$errors[] = 'You forgot to enter your email address.';
	} 
	else {
		$e = mysqli_real_escape_string($conn, trim($_POST['email']));
	}

	if (empty($errors)) { // If everything's OK.

		//  Test for unique email address:
		$q = "SELECT user_id FROM users WHERE email='$e' AND user_id != $id";
		$r = @mysqli_query($conn, $q);
		if (mysqli_num_rows($r) == 0) {

			// Make the query:
			$q = "UPDATE users SET username='$username', email='$e' WHERE user_id=$id LIMIT 1";
			$r = @mysqli_query($conn, $q);
			if (mysqli_affected_rows($conn) == 1) { // If it ran OK.

				// Print a message:
				echo '<p>The user has been edited.</p>';

			} else { // If it did not run OK.
				echo '<p>Same details as before.</p>'; // Public message.
			}

		} else { // Already registered.
			echo '<p>The email address has already been registered.</p>';
		}

	} else { // Report the errors.

		echo '<p class="error-edit">The following error occurred:<br>';
		foreach ($errors as $msg) { // Print each error.
			echo "$msg<br>\n";
		}
		echo '</p><p>Please try again.</p>';

	} // End of if (empty($errors)) IF.

} // End of submit conditional.

// Always show the form...



// Retrieve the user's information:
$q = "SELECT username, email FROM users WHERE user_id=$id";
$r = @mysqli_query($conn, $q);

if (mysqli_num_rows($r) == 1) { // Valid user ID, show the form.

	// Get the user's information:
	$row = mysqli_fetch_array($r, MYSQLI_NUM);

	// Create the form:
	echo '

<form action="edituser.php" method="post">
<p>Username: <input type="text" name="username" value="' . $row[0] . '"></p>
<p>Email: <input type="email" name="email" value="' . $row[1] . '"></p>
<p><input type="submit" name="submit" value="Submit"></p>
<input type="hidden" name="id" value="' . $id . '">
</form>';

} else { // Not a valid user ID.
	header("Location:index.php");
	
}

mysqli_close($conn);

echo'</section>';
echo'</div>';

require 'templates/footer.php';
?>
