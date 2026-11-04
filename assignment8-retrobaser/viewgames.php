<?php /* Done by Student 110 */

$page_title = 'View the Games';
require 'templates/header.php';
require 'mysqli_connect.php';

if ($_SESSION['user_level'] == 0) {
    
    $url = 'https://dikult205.k.uib.no/2020V/groups/gruppe3/assignment8/index.php'; // Define the URL.
    ob_end_clean(); // Delete the buffer.
    header("Location: $url");
    exit(); // Quit the script.   
}

echo 	'<main class="container-viewusers">';
echo	'<section class="content-viewusers">';


echo	'<h1>List of added games</h1>';


// Number of records to show per page:
$display = 10;

// Determine how many pages there are...
if (isset($_GET['p']) && is_numeric($_GET['p'])) { // Already been determined.
	$pages = $_GET['p'];
} else { // Need to determine.
 	// Count the number of records:
	$q = "SELECT COUNT(game_id) FROM videogame";
	$r = @mysqli_query($conn, $q);
	$row = @mysqli_fetch_array($r, MYSQLI_NUM);
	$records = $row[0];
	// Calculate the number of pages...
	if ($records > $display) { // More than 1 page.
		$pages = ceil ($records/$display);
	} else {
		$pages = 1;
	}
} // End of p IF.

// Determine where in the database to start returning results...
if (isset($_GET['s']) && is_numeric($_GET['s'])) {
	$start = $_GET['s'];
} else {
	$start = 0;
}

// Determine the sort...
// Default is by registration date.
$sort = (isset($_GET['sort'])) ? $_GET['sort'] : 'rd';

// Determine the sorting order:
switch ($sort) {
	case 'un':
		$order_by = 'game_name ASC';
		break;
	case 'em':
		$order_by = 'game_developer ASC';
		break;
	case 'rd':
		$order_by = 'game_release_date ASC';
		break;
	default:
		$order_by = 'game_release_date ASC';
		$sort = 'rd';
		break;
}

// Define the query:
$q = "SELECT game_name, game_developer, DATE_FORMAT(game_release_date, '%M %d, %Y') AS dr, game_id FROM videogame ORDER BY $order_by LIMIT $start, $display";
$r = @mysqli_query($conn, $q); // Run the query.



echo '<table>
<thead>
<tr>
    <th scope="col"><a href="viewgames.php?sort=un">Game name</a></th>
    <th scope="col"><a href="viewgames.php?sort=em">Game Developer</a></th>
	<th scope="col"><a href="viewgames.php?sort=rd">Release Date</a></th>
	<th scope="col">Edit Game</th>
	<th scope="col">Delete Game</th>
</tr>
</thead>
<tbody>
';

// Fetch and print all the records....
$bg = '#eeeeee';
while ($row = mysqli_fetch_array($r, MYSQLI_ASSOC)) {
	$bg = ($bg=='#eeeeee' ? '#ffffff' : '#eeeeee');
		echo'
		<tr bgcolor="' . $bg . '">
        <td scope="row" data-label="Game Name">'.$row['game_name'].'</td>
        <td scope="row" data-label="Game Developer">'.$row['game_developer'].'</td>
		<td scope="row" data-label="Release Date">'.$row['dr']. '</td>
		<td scope="row" data-label="Edit"><a href="editgames.php?id='.$row['game_id'].'">Edit</a></td>
		<td scope="row" data-label="Delete"><a href="deletegames.php?id='.$row['game_id'].'">Delete</a></td>

	</tr>
	';
} // End of WHILE loop.
echo '</tbody></table>';

mysqli_free_result($r);
mysqli_close($conn);

// Make the links to other pages, if necessary.
if ($pages > 1) {

	echo '<br><p>';
	$current_page = ($start/$display) + 1;

	// If it's not the first page, make a Previous button:
	if ($current_page != 1) {
		echo '<a href="viewgames.php?s=' . ($start - $display) . '&p=' . $pages . '&sort=' . $sort . '">Previous</a> ';
	}

	// Make all the numbered pages:
	for ($i = 1; $i <= $pages; $i++) {
		if ($i != $current_page) {
			echo '<a href="viewgames.php?s=' . (($display * ($i - 1))) . '&p=' . $pages . '&sort=' . $sort . '">' . $i . '</a> ';
		} else {
			echo $i . ' ';
		}
	} // End of FOR loop.

	// If it's not the last page, make a Next button:
	if ($current_page != $pages) {
		echo '<a href="viewgames.php?s=' . ($start + $display) . '&p=' . $pages . '&sort=' . $sort . '">Next</a>';
	}

	echo '</p>'; // Close the paragraph.

} // End of links section.

// Check for form submission:
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
        require('mysqli_connect.php'); // Connect to the db.
        
        
        // Check for a Game Title:
        if (empty($_POST['game_name'])) {
            $errors[] = 'You forgot to enter a Game Title.';
        } else {
            $gamingname = mysqli_real_escape_string($conn, trim($_POST['game_name']));
        }
        
        // Check for an Game Desctiption:
        if (empty($_POST['game_description'])) {
            $errors[] = 'You forgot to enter a game description.';
        } else {
            $gamingdescription = mysqli_real_escape_string($conn, trim($_POST['game_description']));
        }
        // Check for an Game content:
    
            if (empty($_POST['content'])) {
                $errors[] = 'You forgot to enter the Long Description.';
            } else {
                $content = mysqli_real_escape_string($conn, trim($_POST['content']));
            }

        // Check for an Game developer:
        if (empty($_POST['game_developer'])) {
            $errors[] = 'You forgot to enter a game developer.';
        } else {
            $gamingdeveloper = mysqli_real_escape_string($conn, trim($_POST['game_developer']));
        }
        
        
        // Check for an Release Date:
        if (empty($_POST['game_release_date'])) {
            $errors[] = 'You forgot to enter a release date.';
        } else {
            $gamingreleasedate = mysqli_real_escape_string($conn, trim($_POST['game_release_date']));
        }
        
        
        if (empty($errors)) { // If everything's OK.
            
            // Register the user in the database...
            
            // Make the query:
            $query  = "INSERT INTO videogame (game_name, game_description, content, game_developer, game_release_date) 
            VALUES ('$gamingname', '$gamingdescription','$content','$gamingdeveloper', '$gamingreleasedate')";
            $result = @mysqli_query($conn, $query); // Run the query.
            if ($result) { // If it ran OK.
                
                // Print a message:
                echo '<h1>Game added to the database!</h1>
                <p>You have successfully registered a game.</p>';

                exit();


                
            } else { // If it did not run OK.
                
                // Public message:
                echo '<h1>System Error</h1>
                    <p>The game could not be registered due to a system error. We apologize for any inconvenience.</p>';
                
            } // End of if ($r) IF.
            
            mysqli_close($conn); // Close the database connection.
            
            exit();
            
        } else { // Report the errors.
            
            echo '<h1>Error!</h1>
                <p>The following error occurred:<br>';
            foreach ($errors as $msg) { // Print each error.
                echo " - $msg<br>\n";
            }
            echo '</p><p>Please try again.</p><p><br></p>';
            
        } // End of if (empty($errors)) IF.
        
        mysqli_close($conn); // Close the database connection.
        


    } // End of the main Submit conditional.
    
    echo'
    <h1> Add Game</h1>


    <form action="" method="post">
                <label for="game_name">Game Title:</label> <input name="game_name" type="text"
                placeholder="Game Title">
                <label for="game_description">Short Description:</label> <textarea name=
                "game_description" type="text" placeholder="Short Description for Retro Games list..."></textarea>
                <label for="content">Long Description:</label> <textarea name=
                "content" type="text" placeholder="Long Description for the individual game..."></textarea>
                 <label for="game_developer">Game Developer:</label> <input name=
                "game_developer" type="text" placeholder="Game Developer">
                <label for="game_release_date">Release Date:</label> <input name=
                "game_release_date" type="date" placholder="Release Date"><br>
                 <input name="addgame" type="submit" value="Submit Game" class="SubmitGame-Button"><br>
            </form>
    
    </section>
</main>';

include('templates/footer.php');?>