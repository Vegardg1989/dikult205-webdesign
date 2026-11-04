<?php /* Done by Student 110 */

$page_title = 'Edit game';
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

	// Check for a game name:
	if (empty($_POST['game_name'])) {
		$errors[] = 'You forgot to enter the Game Name.';
	} else {
		$gamename = mysqli_real_escape_string($conn, trim($_POST['game_name']));
	}

	// Check for an Gane  Description:
	if (empty($_POST['game_description'])) {
		$errors[] = 'You forgot to enter your a Game description.';
	} 
	else {
		$gamedescription = mysqli_real_escape_string($conn, trim($_POST['game_description']));
    }
    	// Check for an email address:
	if (empty($_POST['content'])) {
		$errors[] = 'You forgot to enter your a Long a description.';
	} 
	else {
		$content = mysqli_real_escape_string($conn, trim($_POST['content']));
    }
    
        	// Check for an email address:
	if (empty($_POST['game_developer'])) {
		$errors[] = 'You forgot to enter your a Game Developer.';
	} 
	else {
		$gamedeveloper = mysqli_real_escape_string($conn, trim($_POST['game_developer']));
    }
    
            	// Check for an email address:
	if (empty($_POST['game_release_date'])) {
		$errors[] = 'You forgot to enter your a release date.';
	} 
	else {
		$gamereleasedate = mysqli_real_escape_string($conn, trim($_POST['game_release_date']));
	}


	if (empty($errors)) { // If everything's OK.

			// Make the query:
			$q = "UPDATE videogame SET game_name='$gamename', game_description='$gamedescription',content='$content',game_developer='$gamedeveloper',game_release_date='$gamereleasedate' WHERE game_id=$id LIMIT 1";
			$r = @mysqli_query($conn, $q);
			if (mysqli_affected_rows($conn) == 1) { // If it ran OK.


				// Print a message:
				echo '<p>The Game has been edited.</p>';

			} else { // If it did not run OK.
				echo '<p>Same details as before.</p>'; // Public message.
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
$q = "SELECT game_name, game_description, content, game_developer,game_release_date FROM videogame WHERE game_id=$id";
$r = @mysqli_query($conn, $q);

if (mysqli_num_rows($r) == 1) { // Valid user ID, show the form.

	// Get the user's information:
	$row = mysqli_fetch_array($r, MYSQLI_NUM);

	// Create the form:
	echo '

<form action="editgames.php" method="post">
<label for="game_name">Game Title:</label><input type="text" name="game_name" value="' . $row[0] . '">
<label for="game_description">Short Description:</label><textarea type="text" name="game_description"> '. $row[1].'</textarea>
<label for="content">Long Description:</label><textarea type="text" name="content">'. $row[2] .'</textarea>
<label for="game_developer">Game Developer:</label><input type="text" name="game_developer" value="' . $row[3] . '"></p>
<label for="game_release_date">Game Release Date:</label><input type="date" name="game_release_date" value="' . $row[4] . '"></p>

<p><input type="submit" name="submit" value="Submit" class="SubmitGame-Button"></p>
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
