<?php /* Restored in 2026 by Vegard Gundersen with help from Claude (Anthropic's AI assistant).
 * The 2020 server code was lost; only the April 2020 rough draft survived. This file rebuilds the
 * submitted version from the June 2020 portfolio screenshots and reuses the Retrobaser login system. */
$page_title = 'Become a member';
require('templates/header.php');
?>
	<main class="content-landingpage">
		<h2>Become a member</h2>
		<?php include('templates/messages.inc.php'); ?>
		<form action="templates/signup.inc.php" method="post" class="cra-form">
			<label for="username">Name:</label>
			<input type="text" name="username" id="username" value="<?php if (isset($_GET['username'])) echo h($_GET['username']); ?>">
			<label for="email">E-mail:</label>
			<input type="email" name="email" id="email" value="<?php if (isset($_GET['email'])) echo h($_GET['email']); ?>">
			<label for="password">Password:</label>
			<input type="password" name="password" id="password">
			<label for="password_repeat">Repeat Password:</label>
			<input type="password" name="password_repeat" id="password_repeat">
			<input type="submit" name="register-account" value="Sign Up">
		</form>
	</main>
	<aside class="aside-landingpage">
		<img alt="<?php echo isset($aside_alt) ? $aside_alt : 'A hunter aiming a rifle outdoors'; ?>" id="gunrange-landingpage" src="../pictures/<?php echo isset($aside_img) ? $aside_img : 'range2.jpg'; ?>">
		<p><q>A well regulated Militia, being necessary to the security of a free State,
		the right of the people to keep and bear Arms, shall not be infringed.</q></p><span>The Second Amendment</span>
	</aside>
<?php include('templates/footer.php'); ?>
