<?php /* Restored in 2026 by Vegard Gundersen with help from Claude (Anthropic's AI assistant).
 * The 2020 server code was lost; only the April 2020 rough draft survived. This file rebuilds the
 * submitted version from the June 2020 portfolio screenshots and reuses the Retrobaser login system. */
$page_title = 'Change Your Password';
include('templates/header.php');
if (!isset($_SESSION['user_id'])) {
	ob_end_clean();
	header('Location: login.php?error=loginfirst');
	exit();
}
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$p = isset($_POST['password']) ? $_POST['password'] : '';
	if (strlen($p) < 6) {
		$error = 'passwordlength';
	} elseif ($p !== $_POST['password_repeat']) {
		$error = 'passwordcheck';
	} else {
		require('mysqli_connect.php');
		$ph = password_hash($p, PASSWORD_DEFAULT);
		$stmt = mysqli_prepare($conn, 'UPDATE users SET pass=? WHERE user_id=? LIMIT 1');
		mysqli_stmt_bind_param($stmt, 'si', $ph, $_SESSION['user_id']);
		mysqli_stmt_execute($stmt);
		mysqli_close($conn);
		$error = '';
	}
	ob_end_clean();
	header('Location: change_password.php?' . ($error ? 'error=' . $error : 'done=changed'));
	exit();
}
?>
	<main class="content-landingpage">
		<h2>Change Your Password</h2>
		<?php include('templates/messages.inc.php'); ?>
		<form action="change_password.php" method="post" class="cra-form">
			<label for="password">New Password:</label>
			<input type="password" name="password" id="password">
			<label for="password_repeat">Confirm New Password:</label>
			<input type="password" name="password_repeat" id="password_repeat">
			<input type="submit" value="Change My Password">
		</form>
	</main>
	<aside class="aside-landingpage">
		<img alt="<?php echo isset($aside_alt) ? $aside_alt : 'A hunter aiming a rifle outdoors'; ?>" id="gunrange-landingpage" src="../pictures/<?php echo isset($aside_img) ? $aside_img : 'range2.jpg'; ?>">
		<p><q>A well regulated Militia, being necessary to the security of a free State,
		the right of the people to keep and bear Arms, shall not be infringed.</q></p><span>The Second Amendment</span>
	</aside>
<?php include('templates/footer.php'); ?>
