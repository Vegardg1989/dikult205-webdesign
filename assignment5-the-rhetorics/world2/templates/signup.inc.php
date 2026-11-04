<?php /* Restored in 2026 by Vegard Gundersen with help from Claude (Anthropic's AI assistant).
 * The 2020 server code was lost; only the April 2020 rough draft survived. This file rebuilds the
 * submitted version from the June 2020 portfolio screenshots and reuses the Retrobaser login system. */
// Sign-up handler, the same checks as Retrobaser's signup.inc.php.
if (!isset($_POST['register-account'])) {
	header('Location: ../signup.php');
	exit();
}
require('../mysqli_connect.php');
$username = trim($_POST['username']);
$email = trim($_POST['email']);
$password = $_POST['password'];
$password_repeat = $_POST['password_repeat'];
$back = '../signup.php?username=' . urlencode($username) . '&email=' . urlencode($email) . '&error=';

if ($username == '' || $email == '' || $password == '' || $password_repeat == '') {
	header('Location: ' . $back . 'emptyfields'); exit();
} elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
	header('Location: ' . $back . 'invalidemail'); exit();
} elseif (!preg_match('/^[a-zA-Z0-9]*$/', $username)) {
	header('Location: ' . $back . 'invalidusername'); exit();
} elseif (strlen($password) < 6) {
	header('Location: ' . $back . 'passwordlength'); exit();
} elseif ($password !== $password_repeat) {
	header('Location: ' . $back . 'passwordcheck'); exit();
}

foreach (array('username' => 'usernametaken', 'email' => 'emailtaken') as $column => $error) {
	$stmt = mysqli_prepare($conn, "SELECT user_id FROM users WHERE $column=?");
	$value = ($column == 'username') ? $username : $email;
	mysqli_stmt_bind_param($stmt, 's', $value);
	mysqli_stmt_execute($stmt);
	mysqli_stmt_store_result($stmt);
	if (mysqli_stmt_num_rows($stmt) > 0) {
		header('Location: ' . $back . $error); exit();
	}
}

// Create the activation code and the account:
$a = md5(uniqid(rand(), true));
$hash = password_hash($password, PASSWORD_DEFAULT);
$stmt = mysqli_prepare($conn, 'INSERT INTO users (username, email, pass, active) VALUES (?, ?, ?, ?)');
mysqli_stmt_bind_param($stmt, 'ssss', $username, $email, $hash, $a);
if (!mysqli_stmt_execute($stmt)) {
	header('Location: ' . $back . 'sqlerror'); exit();
}

// Send the activation e-mail:
$link = 'https://' . $_SERVER['HTTP_HOST'] . dirname(dirname($_SERVER['PHP_SELF'])) . '/activate.php?x=' . urlencode($email) . '&y=' . $a;
mail($email, 'Activate your CRA membership', "Thank you for registering at the CRA. To activate your account, click this link:\n\n" . $link, 'From: noreply@cra.example');
mysqli_close($conn);
header('Location: ../signup.php?done=signup');
