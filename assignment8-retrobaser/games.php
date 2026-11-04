<?php /* Done by both Student 108 and 110 */


require 'mysqli_connect.php';

$page_title = 'List of Retro Games';
include('templates/header.php');

    $query = "SELECT * FROM videogame";
    $result = $conn->query($query);
    
    while ($row = $result->fetch_assoc()) {
        
        $class = $row['game_name'];
        $class = strtolower($class);
        $color = $row['color'];
    }
?>

<div class="container-rating">


<section class="content-rating">

    <h1>Click your choice of game for more information on the game</h1>
    
<?php

/*========RATING============*/


if (isset($_POST['sort'])) { /* Sort done by both Student 108 and 110 */
    
    $query = "SELECT * FROM videogame";
    $sort = $_POST['sort'];
    if ($sort == "title-ascending") {
        $query = "SELECT * FROM videogame ORDER BY game_name ASC";
    } elseif ($sort == "title-descending") {    
        $query = "SELECT * FROM videogame ORDER BY game_name DESC";
    } elseif ($sort == "created-descending") {
		$query = "SELECT * FROM videogame ORDER BY game_release_date DESC";
    } elseif ($sort == "created-ascending") {
		$query = "SELECT * FROM videogame ORDER BY game_release_date ASC";
    }
}
    $result = $conn->query($query);
    echo
    '<form action="games.php" method="POST">
    <select name="sort" class="sortlist">
        <option value="manual">Default</option>
        <option value="title-ascending" selected>Alphabetically: A-Z</option>
        <option value="title-descending">Alphabetically: Z-A</option>
        <option value="created-descending">Date: New to Old</option>
        <option value="created-ascending">Date: Old to New</option>
    </select>
    <input type="submit" name="submit" value="Sort Retro Games" class="sortbutton">

</form>'; /* End of sort */
    while ($row = $result->fetch_assoc()) { /* Rating part 1 done by Student 108 */
        $id = $row['game_id'];
        $game_name = $row['game_name'];
        $game_description = $row['game_description'];
        $game_developer = $row['game_developer'];
        $game_release_date = $row['game_release_date'];

        $classname = strtolower($game_name);
        $class = "class=\"$classname\"";
        echo   
             "
            <!-- NEW ARTICLE -->
            <article $class>
            <h2><a href='games/retrogames.php?id=".$row['game_id']."' class='gamebutton'>$game_name</a></h2>
            <p>$game_description, $game_developer, $game_release_date</p>\n";
    
        $ratingquery = "SELECT ROUND(AVG(rating_stars),1) AS avgstars FROM videogame_ratings WHERE video_game=$id";
        $avg = $conn->query($ratingquery)->fetch_assoc()['avgstars'];
    
    if ($avg) {
        echo "
        <p class='rating'>Rating: ";
        
        if ($avg < 1.5) { $source = "stars/stars20.svg"; }
        else if ($avg < 2.5) { $source = "stars/stars40.svg"; }
        else if ($avg < 3.5) { $source = "stars/stars60.svg"; }
        else if ($avg < 4.5) { $source = "stars/stars80.svg"; }
        else { $source = "stars/stars100.svg"; }
        
        echo "<img src='$source' alt='$avg stars' class='stars'> ($avg)</p>";
    
    } else {
        echo "
        <p>(No rating yet)</p>\n";
    } /* Rating part 1 end */


    if (isset($_SESSION['user_id'])) { /* Rating part 2 done by 110 */
		
		/* give one star */
		
		echo "\n<form class=\"stars\" method=\"POST\" action=\"\">\n"; 
		echo "<input type=\"hidden\" name=\"video_game\" value=\"$id\" >"; 
		echo "<input type=\"hidden\" name=\"rating_stars\" value=\"1\" >"; 
		echo "<button class=\"ratinginput\" title=\"Give one star\">
				<img src='icons/star.svg'></button>"; 
		echo "</form>\n"; 					

		/* give two stars */
		echo "\n<form class=\"stars\" method=\"POST\" action=\"\">\n"; 
		echo "<input type=\"hidden\" name=\"video_game\" value=\"$id\" >"; 
		echo "<input type=\"hidden\" name=\"rating_stars\" value=\"2\" >"; 
		echo "<button class=\"ratinginput\" title=\"Give two stars\">
		<img  src='icons/star.svg'></button>"; 
		echo "</form>\n"; 

		/* give three stars */
		echo "\n<form class=\"stars\" method=\"POST\" action=\"\">\n"; 
		echo "<input type=\"hidden\" name=\"video_game\" value=\"$id\" >"; 
		echo "<input type=\"hidden\" name=\"rating_stars\" value=\"3\" >"; 
		echo "<button class=\"ratinginput\" title=\"Give three stars\">
		<img src='icons/star.svg'></button>"; 
		echo "</form>\n"; 
		
		/* give four stars */
		echo "\n<form class=\"stars\" method=\"POST\" action=\"\">\n"; 
		echo "<input type=\"hidden\" name=\"video_game\" value=\"$id\" >"; 
		echo "<input type=\"hidden\" name=\"rating_stars\" value=\"4\" >"; 
		echo "<button class=\"ratinginput\" title=\"Give four stars\">
		<img src='icons/star.svg'></button>"; 
		echo "</form>\n";
		
		/* give five stars */
		echo "\n<form class=\"stars\" method=\"POST\" action=\"\">\n"; 
		echo "<input type=\"hidden\" name=\"video_game\" value=\"$id\" >"; 
		echo "<input type=\"hidden\" name=\"rating_stars\" value=\"5\" >"; 
		echo "<button class=\"ratinginput\" title=\"Give five stars\">
		<img src='icons/star.svg'></button>"; 
        echo "</form>\n";
        
		
    }	
    if (isset($_SESSION['user_id']) && isset($_POST['video_game'])) {
		
		$game = $_POST['video_game']; 
		$stars = $_POST['rating_stars']; 			
		$cookieuser = $_SESSION['user_id'];
		$query = "SELECT * FROM videogame_ratings WHERE user_id = $cookieuser AND video_game = $game"; 		
		$result = $conn->query($query); 
		
		if ($result->num_rows > 0) {
			// found an earlier rating. Update.
			$query = "UPDATE videogame_ratings SET rating_stars = $stars 
				WHERE (user_id = $cookieuser AND video_game = $game)"; 
		} else {
			// first rating. Insert. 
			$query = "INSERT INTO videogame_ratings (user_id, video_game, rating_stars) 
				VALUES ($cookieuser, $game, $stars)"; 
		}

		$result = $conn->query($query);
		header("Location:games.php");
    }
    echo "</article>\n"; /* Rating part 2 end */

 }
?>
</section>
</div>

<?php
include ('templates/footer.php');
?>