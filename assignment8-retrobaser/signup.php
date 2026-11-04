<?php /* Done by Student 110 */

$page_title = 'Create an account at Retrobaser';


require 'templates/header.php'; ?>


<div class="forms-container">    

<section class="forms-section">
<h1>Sign Up</h1>
<form action="templates/signup.inc.php" method="post">
    <!-- ERROR MESSAGES FOR THE SIGN-UP FORM -->
	<?php if (isset($_GET['error']))  {
		if ($_GET['error'] == "emptyfields") {
			echo '<p> Fill in all fields</p>';
		}
		elseif ($_GET['error'] == "invalidemail") {
			echo '<p>Invalid e-mail </p>';
		}
		elseif ($_GET['error'] == "invalidusername") {
			echo '<p>Invalid username </p>';
		}
		elseif ($_GET['error'] == "passwordlength") {
			echo '<p>Password to short</p>';
		}
		elseif ($_GET['error'] == "passwordcheck") {
			echo '<p>Your passwords did not match</p>';
		} 
		elseif ($_GET['error'] == "usernametaken") {
			echo '<p>Username is already taken, please choose another</p>';
		} 
		elseif ($_GET['error'] == "emailtaken") {
			echo '<p>Username is already registered. If you have forgotten your password use link above</p>';
		} 
		elseif ($_GET['error'] == "sqleror") {
			echo '<p>systemerror</p>';
		} 	
	}
	if (isset($_GET['signup'])) {
		if ($_GET['signup'] == "success") {
		echo '<h3>Thank you for registering!</h3>
		 <p> A confirmation email has been sent to your address.
		Please click on the link in the email in order to activate your account.</p>
		';
		}
	}

	?>
	<label for="username">Username:</label>
	<input type="text" name="username" id="username" value="<?php if (isset($_GET['error'])) echo $_GET['username']; ?>">
	<label for="email">E-mail:</label>
	<input type="email" name="email" value="<?php if (isset($_GET['error'])) echo $_GET['email']; ?>">
	<label for="password">Password:</label>
	<input type="password" name="password">
	<label for="password_repeat">Repeat Password:</label>
	 <input type="password" name="password_repeat" id="password_repeat">
	 <input type="submit" name="register-account" value="Sign Up" class="SignUp-Button">
</form>

</section>

<?php 



?>

</div>


<?php include('templates/footer.php'); ?>