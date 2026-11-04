<?php /* Restored in 2026 by Vegard Gundersen with help from Claude (Anthropic's AI assistant).
 * The 2020 server code was lost; only the April 2020 rough draft survived. This file rebuilds the
 * submitted version from the June 2020 portfolio screenshots and reuses the Retrobaser login system. */
// Activates an account from the e-mailed link, like Retrobaser's activate.php.
$x = isset($_GET['x']) ? $_GET['x'] : '';
$y = isset($_GET['y']) ? $_GET['y'] : '';
if (filter_var($x, FILTER_VALIDATE_EMAIL) && strlen($y) == 32) {
	require('mysqli_connect.php');
	$stmt = mysqli_prepare($conn, 'UPDATE users SET active=NULL WHERE email=? AND active=? LIMIT 1');
	mysqli_stmt_bind_param($stmt, 'ss', $x, $y);
	mysqli_stmt_execute($stmt);
	$ok = mysqli_stmt_affected_rows($stmt) == 1;
	mysqli_close($conn);
	if ($ok) {
		header('Location: login.php?done=activated');
		exit();
	}
}
header('Location: index.php?error=sqlerror');
