<?php /* Done by Student 110 */

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$page_title = 'Forgot Your Password';
include('templates/header.php');

?>

<div class="forms-container">

<section class="forms-section">

<h1>Forgot Password</h1>
    
<p>Please fill out your E-mail and we will be in touch.</p>

<form action="templates/forgot_password.inc.php" method="post">
<?php

if (isset($_GET['error']))  {
		if ($_GET['error'] == "emailnomatch") {
			echo '<p> Email no match</p>';
	}
	elseif ($_GET['error'] == "emptyemail") {
		echo '<p> Empty email </p>';
	}
	elseif ($_GET['error'] == "system") {
		echo '<p> System error </p>';
	}
	elseif ($_GET['error'] == "failedvailidation") {
		echo '<p> Please try again </p>';
	}		
}
if (isset($_GET['retrive']))  {
	if ($_GET['retrive'] == "sucess") {
		echo '<p> Sucess! An email has been sent to your address.</p>';
	}
}

?>
	<label for="email">E-mail:</label>		
	<input type="email" name="email">
	<input type="submit" name="submit" value="Submit E-mail" class="SubmitEmail-Button">
</form>
</section>
</div>
<?php include('templates/footer.php'); 

?>