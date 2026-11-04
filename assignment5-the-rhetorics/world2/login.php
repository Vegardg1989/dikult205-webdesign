<?php /* Restored in 2026 by Vegard Gundersen with help from Claude (Anthropic's AI assistant).
 * The 2020 server code was lost; only the April 2020 rough draft survived. This file rebuilds the
 * submitted version from the June 2020 portfolio screenshots and reuses the Retrobaser login system. */
// The login page, the same logic as Retrobaser's login.php.
$page_title = 'Login';
include('templates/header.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	require('mysqli_connect.php');
	$email = isset($_POST['email']) ? trim($_POST['email']) : '';
	$pwd = isset($_POST['pass']) ? trim($_POST['pass']) : '';
	if ($email == '' || $pwd == '') {
		header('Location: login.php?error=emptyfields');
		exit();
	}
	$stmt = mysqli_prepare($conn, 'SELECT user_id, username, user_level, pass FROM users WHERE email=? AND active IS NULL');
	mysqli_stmt_bind_param($stmt, 's', $email);
	mysqli_stmt_execute($stmt);
	mysqli_stmt_bind_result($stmt, $user_id, $username, $user_level, $pass);
	if (mysqli_stmt_fetch($stmt) && password_verify($pwd, $pass)) {
		session_regenerate_id(true);
		$_SESSION['user_id'] = $user_id;
		$_SESSION['username'] = $username;
		$_SESSION['user_level'] = $user_level;
		mysqli_close($conn);
		ob_end_clean();
		header('Location: index.php');
		exit();
	}
	mysqli_close($conn);
	header('Location: login.php?error=nomatch');
	exit();
}
?>
	<main class="content-landingpage">
		<h2>Login</h2>
		<?php include('templates/messages.inc.php'); ?>
		<form action="login.php" method="post" class="cra-form">
			<label for="email">E-mail</label>
			<input type="email" id="email" name="email">
			<label for="password">Password</label>
			<input type="password" id="password" name="pass">
			<button type="submit" name="submit">Login</button>
		</form>
		<p><a href="forgot_password.php">Forgot Password</a></p>
	</main>
	<aside class="aside-landingpage">
		<img alt="<?php echo isset($aside_alt) ? $aside_alt : 'A hunter aiming a rifle outdoors'; ?>" id="gunrange-landingpage" src="../pictures/<?php echo isset($aside_img) ? $aside_img : 'range2.jpg'; ?>">
		<p><q>A well regulated Militia, being necessary to the security of a free State,
		the right of the people to keep and bear Arms, shall not be infringed.</q></p><span>The Second Amendment</span>
	</aside>
<?php include('templates/footer.php'); ?>
