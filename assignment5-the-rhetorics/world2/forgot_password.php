<?php /* Restored in 2026 by Vegard Gundersen with help from Claude (Anthropic's AI assistant).
 * The 2020 server code was lost; only the April 2020 rough draft survived. This file rebuilds the
 * submitted version from the June 2020 portfolio screenshots and reuses the Retrobaser login system. */
// E-mails a new, temporary password, like Retrobaser's forgot_password.php.
$page_title = 'Forgot Your Password';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	require('mysqli_connect.php');
	$email = isset($_POST['email']) ? trim($_POST['email']) : '';
	$stmt = mysqli_prepare($conn, 'SELECT user_id FROM users WHERE email=?');
	mysqli_stmt_bind_param($stmt, 's', $email);
	mysqli_stmt_execute($stmt);
	mysqli_stmt_bind_result($stmt, $uid);
	if (!mysqli_stmt_fetch($stmt)) {
		header('Location: forgot_password.php?error=emailnomatch');
		exit();
	}
	mysqli_stmt_close($stmt);
	$p = substr(bin2hex(random_bytes(8)), 0, 12);
	$ph = password_hash($p, PASSWORD_DEFAULT);
	$stmt = mysqli_prepare($conn, 'UPDATE users SET pass=? WHERE user_id=? LIMIT 1');
	mysqli_stmt_bind_param($stmt, 'si', $ph, $uid);
	mysqli_stmt_execute($stmt);
	mail($email, 'Your temporary CRA password', "Your password has been temporarily changed to '$p'. Log in with it and then change it.", 'From: noreply@cra.example');
	mysqli_close($conn);
	header('Location: forgot_password.php?done=reset');
	exit();
}
include('templates/header.php');
?>
	<main class="content-landingpage">
		<h2>Reset Your Password</h2>
		<?php include('templates/messages.inc.php'); ?>
		<p>Enter your e-mail address and a temporary password will be sent to you.</p>
		<form action="forgot_password.php" method="post" class="cra-form">
			<label for="email">E-mail:</label>
			<input type="email" name="email" id="email">
			<input type="submit" value="Reset My Password">
		</form>
	</main>
	<aside class="aside-landingpage">
		<img alt="<?php echo isset($aside_alt) ? $aside_alt : 'A hunter aiming a rifle outdoors'; ?>" id="gunrange-landingpage" src="../pictures/<?php echo isset($aside_img) ? $aside_img : 'range2.jpg'; ?>">
		<p><q>A well regulated Militia, being necessary to the security of a free State,
		the right of the people to keep and bear Arms, shall not be infringed.</q></p><span>The Second Amendment</span>
	</aside>
<?php include('templates/footer.php'); ?>
