<?php // Done by both Student 108 and 110, see responsibility in portfolio for more information

$page_title = 'Games';
date_default_timezone_set('Europe/Oslo');
require '../mysqli_connect.php';
require '../templates/header_games.php';
include '../videogame_comments.php';

if ( (isset($_GET['id'])) && (is_numeric($_GET['id'])) ) { // From games.php
	$id = $_GET['id'];

}
?>


    
<div class="container-games">

<div class="content-games">

<?php

/*==========IMAGE UPLOAD==========*/



if (isset($_POST['upload'])) {
    $image = $_FILES['image']['name'];
    $image_description = $_POST['image_description'];
    
    $target = "../images/".basename($image); // Upload image to the images folder on turing
    
    $sql = "INSERT INTO images (pid, image, image_description) VALUES ($id, '$image', '$image_description')";
    $result = $conn->query($sql); // Run the query.
    header("Location: retrogames.php?id=$id"); // Redirect user to games site
    
    if (move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
        echo "The image has uploaded successfully!"; // If upload worked show this
    }else{
        echo "Image failed to upload, please try again."; // Else show this if failed
    }
}

if ( (isset($_GET['id'])) && (is_numeric($_GET['id'])) ) { // From games.php
	$id = $_GET['id'];


$sql = "SELECT * FROM images WHERE pid=$id"; // Select the image where picture id is as games id
$result = $conn->query($sql); // Run the query.

/*==========POST IMAGE FORM==========*/

    echo "<div id='content'>";
            while ($row = mysqli_fetch_array($result)) {
            echo "<div class='img_div'>";
            echo "<img src='../images/".$row['image']."' alt='game_images'>";
            echo "<p>".$row['image_description']."</p>";
            echo "</div>";
}
    if (isset($_SESSION['user_id'])) {
        if ($_SESSION['user_level'] == 1) // Only admin can use images
            echo "<form method='POST' action='retrogames.php?id=$id' enctype='multipart/form-data'>
  	             <input type='hidden' name='size' value='1000000'>
  	             <input type='file' name='image'>
  	     
                <textarea name='image_description' placeholder='Give the image a description...'></textarea>
  	            <button type='submit' class='imageButton' name='upload'>Post a image</button>
                    
                <form class='imageDeleteButton' method='POST' action='".deleteimage($conn)."'>
                <button type='submit' class='imageButton' name='imageDelete'>Delete all images</button>
                </form>"; // Form for posting and deleting image
}
}
/*==========DELETE IMAGE==========*/

function deleteImage($conn) {
    if ( (isset($_GET['id'])) && (is_numeric($_GET['id'])) ) { // From games.php
        $id = $_GET['id'];
    
    }
    if (isset($_POST['imageDelete'])) {

        $sql = "DELETE FROM images WHERE pid=$id"; // Delete image where picture id is the same as games id
        $result = $conn->query($sql); // Run the query.
        header("Location: retrogames.php?id=$id"); // Redirect user to games site
    }
}

?>    

<?php

$query = "SELECT * FROM videogame WHERE game_id=$id";
$result = $conn->query($query);
while ($row = $result->fetch_assoc()) {
    $game_name = $row['game_name'];
    $game_description = $row['game_description'];
    $content = $row['content'];
    $game_developer = $row['game_developer'];
    $game_release_date = $row['game_release_date'];

    $classname = strtolower($game_name);
    $class = "class=\"$classname\"";
    echo   
         "
        <!-- NEW ARTICLE -->
        <article $class>
        <h2>$game_name</h2>

        <p>$content, $game_developer, $game_release_date</p>\n";
        $ratingquery = "SELECT ROUND(AVG(rating_stars),1) AS avgstars FROM videogame_ratings WHERE video_game=$id";
        $avg = $conn->query($ratingquery)->fetch_assoc()['avgstars'];

        if ($avg) {
            echo "
            <p class='rating'>Rating: ";
            
            if ($avg < 1.5) { $source = "../stars/stars20.svg"; }
            else if ($avg < 2.5) { $source = "../stars/stars40.svg"; }
            else if ($avg < 3.5) { $source = "../stars/stars60.svg"; }
            else if ($avg < 4.5) { $source = "../stars/stars80.svg"; }
            else { $source = "../stars/stars100.svg"; }
            
            echo "<img src='$source' alt='$avg stars' class='stars'> ($avg)</p>";
        
        } else {
            echo "
            <p>(No rating yet)</p>\n";
        }
    
    
        if (isset($_SESSION['user_id'])) {
            
            /* give one star */
            
            echo "\n<form class=\"stars\" method=\"POST\" action=\"\">\n"; 
            echo "<input type=\"hidden\" name=\"video_game\" value=\"$id\" >"; 
            echo "<input type=\"hidden\" name=\"rating_stars\" value=\"1\" >"; 
            echo "<button class=\"ratinginput\" title=\"Give one star\">
                    <img src='../icons/star.svg'></button>"; 
            echo "</form>\n"; 					
    
            /* give two stars */
            echo "\n<form class=\"stars\" method=\"POST\" action=\"\">\n"; 
            echo "<input type=\"hidden\" name=\"video_game\" value=\"$id\" >"; 
            echo "<input type=\"hidden\" name=\"rating_stars\" value=\"2\" >"; 
            echo "<button class=\"ratinginput\" title=\"Give two stars\">
            <img  src='../icons/star.svg'></button>"; 
            echo "</form>\n"; 
    
            /* give three stars */
            echo "\n<form class=\"stars\" method=\"POST\" action=\"\">\n"; 
            echo "<input type=\"hidden\" name=\"video_game\" value=\"$id\" >"; 
            echo "<input type=\"hidden\" name=\"rating_stars\" value=\"3\" >"; 
            echo "<button class=\"ratinginput\" title=\"Give three stars\">
            <img src='../icons/star.svg'></button>"; 
            echo "</form>\n"; 
            
            /* give four stars */
            echo "\n<form class=\"stars\" method=\"POST\" action=\"\">\n"; 
            echo "<input type=\"hidden\" name=\"video_game\" value=\"$id\" >"; 
            echo "<input type=\"hidden\" name=\"rating_stars\" value=\"4\" >"; 
            echo "<button class=\"ratinginput\" title=\"Give four stars\">
            <img src='../icons/star.svg'></button>"; 
            echo "</form>\n";
            
            /* give five stars */
            echo "\n<form class=\"stars\" method=\"POST\" action=\"\">\n"; 
            echo "<input type=\"hidden\" name=\"video_game\" value=\"$id\" >"; 
            echo "<input type=\"hidden\" name=\"rating_stars\" value=\"5\" >"; 
            echo "<button class=\"ratinginput\" title=\"Give five stars\">
            <img src='../icons/star.svg'></button>"; 
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
            header("Location:retrogames.php?id=$id");
        }
        echo "</article>\n";
    
     }
    ?>
    </div>

<?php

/*==========COMMENT FORM==========*/

if (isset($_SESSION['user_id'])) {
    echo "<form method='POST' action='".setComments($conn)."'>
        <input type='hidden' name='uid' value='".$_SESSION['user_id']."'>
        <input type='hidden' name='date' value='".date('Y-m-d H:i:s')."'>
        <textarea name='comment'></textarea><br>
        <button type='submit' class='commentButton' name='commentSubmit'>Post a comment</button>
    </form>"; // If logged in show form for posting comment
} else {
    echo "<p>If you want to post a comment, please sign up and log in.</p>"; // If not logged in show this
}

getComments($conn);
echo'</div>';

/*==========POST COMMENT==========*/

function setComments($conn) {
    if ( (isset($_GET['id'])) && (is_numeric($_GET['id'])) ) { // From games.php
        $id = $_GET['id'];
    
    
    if (isset($_POST['commentSubmit'])) {
        $uid = mysqli_real_escape_string($conn, trim($_POST['uid']));
        $date = mysqli_real_escape_string($conn, trim($_POST['date']));

        if (empty($_POST['comment'])) {
            $errors[] = 'You forgot to put in text.';
        } else {
            $comment = mysqli_real_escape_string($conn, trim($_POST['comment']));
        }
                
        // Sanitizize data 
        
        $uid= filter_var($uid, FILTER_SANITIZE_STRING);
        $date= filter_var($date, FILTER_SANITIZE_STRING);
        $comment= filter_var($comment, FILTER_SANITIZE_STRING);


        if(empty($errors)){

            $sql = "INSERT INTO videogame_comments (uid, cgid, date, comment) VALUES ('$uid', $id, '$date', '$comment')";
            $result = @mysqli_query($conn, $sql); // Run the query.
        }else {
            echo '<p>Error</p>';
        }

    }
}
}
/*==========GET COMMENT==========*/

function getComments($conn) {
    if ( (isset($_GET['id'])) && (is_numeric($_GET['id'])) ) { // From games.php
        $id = $_GET['id'];
    }
    $sql = "SELECT * FROM videogame_comments WHERE cgid=$id ORDER BY date DESC"; // Select from comments in database and order by date DESC
    $result = $conn->query($sql); // Run the query.
    while ($row = $result->fetch_assoc()) {
        $id = $row['uid'];
        $sqlpart2 = "SELECT * FROM users WHERE user_id='$id'"; // Select from users in database and where the user_id is same as id.
        $resultpart2 = $conn->query($sqlpart2); // Run the query.
        if ($rowpart2 = $resultpart2->fetch_assoc()) {
            echo "<div class='commentBox'><p>";
            echo $rowpart2['username']."<br>"; // The username shown after post comment
            echo $row['date']."<br>"; // The date shown after post comment
            echo nl2br($row['comment']); // The comment itself shown after post comment
            echo "</p>";
            if (isset($_SESSION['user_id'])) {
                if ($_SESSION['user_id'] == $rowpart2['user_id'])
                    echo "<form class='commentEditButton' method='POST' action='../editgamecomment.php'>
                        <input type='hidden' name='cid' value='".$row['cid']."'>
                        <input type='hidden' name='uid' value='".$row['uid']."'>
                        <input type='hidden' name='date' value='".$row['date']."'>
                        <input type='hidden' name='comment' value='".$row['comment']."'>
                        <button>Edit</button>
                    </form>";
            if (isset($_SESSION['user_id'])) {
                if ($_SESSION['user_level'] == 1)
                    echo "<form class='commentDeleteButton' method='POST' action='".deleteComments($conn)."'>
                        <input type='hidden' name='cid' value='".$row['cid']."'>
                        <button type='submit' name='commentDelete'>Delete</button>
                    </form>"; // The form for editing comment and delete button.
                }
            }
         echo "</div>";
        }
    }
}
echo "</div>";
echo "</body>";
echo "</html>";
?>