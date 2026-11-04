<?php /* Done by Student 110 */
ob_start();
session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
  <meta content="noindex,nofollow" name="robots">
  <meta content="noarchive" name="robots">
	<meta content="width=device-width, initial-scale=1" name="viewport">
	<link href="css/style.css" rel="stylesheet" type="text/css">
	<link href="https://fonts.googleapis.com/css2?family=Audiowide&display=swap" rel="stylesheet">
	<title><?php echo $page_title ?></title>
</head>
<body class="container-body">
	<header>
	<div class="logo"><a href="index.php"><img id="logo-image" src="icons/retrobaser.svg" alt=""></a>
</div>


<div class="loginbox">
	<?php
		// Welcome the user (by name if they are logged in):
if (isset($_SESSION['username'])) {
	echo"<h1>Welcome  {$_SESSION['username']} </h1>";
}

	if (isset($_SESSION['user_id'])) {
		echo '<form action="logout.php" method="post">
			</form>';
	}
	else {
		echo '<form action="login.php" method="post"><label for="email">E-mail</label>
				<input type="email" id="email" name="email" placeholder="">
				<label for="password">Password</label>
				<input type="password" id="password" name="pass" placeholder=""><br>
				<div class="login-nav">
				<button type="submit" name="submit">Login</button>
				</div>
                <br>
                
                <a href="forgot_password.php" class="forgotpassbutton">Forgot Password</a>
				</form>';	
	}


	 ?>
</div>
</header>

	<?php
	// Display links based upon the login status:
	if (isset($_SESSION['user_id'])) {
		if ($_SESSION['user_level'] == 1) {
			echo '<nav><input id="menu-toggle" type="checkbox"> <label class="hamburger" for=
			"menu-toggle"><img alt="Menu Icon" src="icons/hamburger.svg"></label>
			<div class="nav-links">
			<a href="index.php">Home</a>
			<a href="games.php">Retro Games</a>
            <a href="comments.php">Discuss/Suggest</a>
			<a href="viewgames.php">Modify Games</a>
			<a href="viewusers.php">User Accounts</a>
			 <a href="change_password.php">Change Password</a>		
			 <a href="logout.php">Log Out</a>
			 </div></nav>';	
		}	
		if	($_SESSION['user_level'] == 0) {	 
		echo'<nav><input id="menu-toggle" type="checkbox"> <label class="hamburger" for=
		"menu-toggle"><img alt="Menu Icon" src="icons/hamburger.svg"></label>
		<div class="nav-links">
		<a href="index.php">Home</a>
		<a href="games.php">Retro Games</a>
		<a href="comments.php">Discuss/Suggest</a>
        <a href="change_password.php">Change Password</a>		
        <a href="logout.php">Log Out</a>
        </div></nav>';
	}
		
	}else {
			echo '<nav><input id="menu-toggle" type="checkbox"> <label class="hamburger" for=
			"menu-toggle"><img alt="Menu Icon" src="icons/hamburger.svg"></label>
			<div class="nav-links">
			<a href="index.php">Home</a>
			<a href="games.php">Retro Games</a>
            <a href="comments.php">Discuss/Suggest</a>
			 <a href="signup.php">Sign Up</a>
			 </div></nav>';		
		}
		
	?>