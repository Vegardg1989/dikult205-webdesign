<?php /* Done by Student 110 */
// This is the login page for the site.
/* =======DEBUGING========*/

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include('templates/header.php');



if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	require('mysqli_connect.php');

	// Validate the email address:
	if (!empty($_POST['email'])) {
		$email = mysqli_real_escape_string($conn, $_POST['email']);
	} else {
		$email = FALSE;
		header("location: index.php?error=emptyfields");
        exit();
	}

	// Validate the password:
	if (!empty($_POST['pass'])) {
		$pwd = trim($_POST['pass']);
	} else {
		$pwd = FALSE;
		echo '<p>You forgot to enter your password!</p>';
	}

	if ($email && $pwd) { // If everything's OK.

		// Query the database
		$query = "SELECT user_id,username,user_level,pass FROM users WHERE email='$email' AND active IS NULL";
		$result = mysqli_query($conn, $query) or trigger_error("Query: $query\n<br>Could not connect to the database " . mysqli_error($conn));

		if (@mysqli_num_rows($result) == 1) { // A match was made.

			// Fetch the values:
			list($user_id,$username,$user_level,$pass) = mysqli_fetch_array($result, MYSQLI_NUM);
			mysqli_free_result($result);

			// Check the password:
			if (password_verify($pwd, $pass)) {

				// Store the info in the session:
				$_SESSION['user_id'] = $user_id;
				$_SESSION['username'] = $username;
				$_SESSION['user_level'] = $user_level;
				mysqli_close($conn);

				// Redirect the user:
				$url = 'https://dikult205.k.uib.no/2020V/groups/gruppe3/assignment8/index.php'; // Define the URL.
				ob_end_clean(); // Delete the buffer.
				header("Location: $url");
				exit(); // Quit the script.

			} else {

				echo '<p>Either the email address or password entered do not match those on file or you have not yet activated your account. Please try again.</p>';
			}

		} else { // No match was made.
			echo '<p>Either the email address or password entered do not match those on file or you have not yet activated your account. Please try again.</p>';
		}

	} else { // If everything wasn't OK.
		echo '<p>Please try again.</p>';
	}

	mysqli_close($conn);

} // End of SUBMIT conditional.


