<?php /* Done by both Student 108 and 110 */

/* =======DEBUGGING========*/

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require 'mysqli_connect.php';

$page_title = 'Retrobaser';
include('templates/header.php');

?>

<div class="content-landingpage">

<section class="information-landingpage">

<h2>Welcome to Retrobaser!</h2>

<p class="welcome">
We here at Retrobaser give you the option to add your favorite games from the retro area. 
It is community-driven, meaning the input of our users is important to make the site grow. 
After signing up (which please do if you are interested) you can go ahead in the discussion section and give out your suggestion on games you want to have on Retrobaser. 
The admins will then have a look, and if it is a suitable retro game, we will add it to the website. 
Already, we have added several games, you the user can go ahead and rate them and give the games a comment, why do you like it, why don't you like it? 
Once again, welcome to the newly established, Retrobaser!
</p>
<p class="madeby"> Made by student 108 and student 110.</p>

</section>

<div class="aside-landingpage">

<video width="560" height="315" controls>
    <source src="videos/lotus2.mp4" type="video/mp4">
    Your browser does not support the video tag.
</video>
<p class="video-text">Intro for the retro game Lotus Turbo Challenge 2</p>

</div>

</div>

<?php include('templates/footer.php'); ?>