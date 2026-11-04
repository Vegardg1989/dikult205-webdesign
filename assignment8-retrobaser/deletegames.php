<?php /* Done by Student 110 */
$page_title = 'Delete Game';
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

echo'<h1>Delete a game</h1>';


// Check if the form has been submitted:
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

	if ($_POST['sure'] == 'Yes') { // Delete the record.

		// Make the query:
		$q = "DELETE FROM videogame WHERE game_id=$id LIMIT 1";
		$r = @mysqli_query($conn, $q);
		if (mysqli_affected_rows($conn) == 1) { // If it ran OK.

			// Print a message:
			echo '<p>The game has now been deleted from the database.</p>';
			echo'<img class"delete-game" src="icons/retrostick.jpg">';


		} else { // If the query did not run OK.
			echo '<p class="error">The game could not be deleted due to a system error.</p>'; // Public message.
			echo'<img class="delete-game" src="icons/retrostick.jpg">';

		}

	} else { // No confirmation of deletion.
		echo '<p>The game has not been deleted.</p>';
		echo'<img class="delete-game" src="icons/retrostick.jpg">';
	}

} else { // Show the form.

	// Retrieve the user's information:
	$q = "SELECT game_name FROM videogame WHERE game_id=$id";
	$r = @mysqli_query($conn, $q);

	if (mysqli_num_rows($r) == 1) { // Valid game ID, show the form.

		// Get the user's information:
		$row = mysqli_fetch_array($r, MYSQLI_NUM);

		// Display the record being deleted:
		echo "<h2>Game Name: $row[0]</h2>
		<p>Are you sure you want to delete this game?.</p>";

		// Create the form:
		echo '<form action="deletegames.php" method="post">
	<input type="radio" class="radioButton" name="sure" value="Yes"> Yes
	<input type="radio" class="radioButton" name="sure" value="No" checked="checked"> No
	<input type="submit" class="deleteuserButton" name="submit" value="Submit">
	<input type="hidden" name="id" value="' . $id . '">
	</form>';
	echo'<img class="delete-game" src="icons/retrostick.jpg">';

	} else { // Not a valid user ID.
		header("Location:index.php");
		exit();
	}

} // End of the main submission conditional.

mysqli_close($conn);

echo '</section>';
echo '</div>';

require 'templates/footer.php';
?>