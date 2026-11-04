<?php /* Restored in 2026 by Vegard Gundersen with help from Claude (Anthropic's AI assistant).
 * The 2020 server code was lost; only the April 2020 rough draft survived. This file rebuilds the
 * submitted version from the June 2020 portfolio screenshots and reuses the Retrobaser login system. */
ob_start();
session_start();
if (!isset($page_title)) { $page_title = 'Home'; }
$here = basename($_SERVER['PHP_SELF']);
function nav_link($file, $label, $here) {
	$active = ($file == $here) ? ' class="active"' : '';
	return "<a$active href=\"$file\">$label</a> ";
}
function h($text) { return htmlspecialchars($text, ENT_QUOTES, 'UTF-8'); }
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta content="noindex,nofollow" name="robots">
	<meta content="noarchive" name="robots">
	<meta content="width=device-width, initial-scale=1" name="viewport">
	<link href="../css/style1.css" rel="stylesheet" type="text/css">
	<link href="https://fonts.googleapis.com/css?family=Audiowide&display=swap&subset=latin-ext" rel="stylesheet">
	<script src="nv.js"></script>
	<title><?php echo h($page_title); ?> · CRA</title>
</head>
<body class="container-landingpage">
	<header>
		<a href="index.php"><img alt="CRA logo" class="icon-header" src="../icons/cra-logo-fictional.svg"></a>
		<section class="header-title">
			<h1>CHICAGO RIFLE ASSOCIATION</h1>
			<p>Dedicated to education, safety training.</p><span>and support of Chicago's firearm owners</span>
		</section>
		<aside class="assosiates">
			<h2>OUR PARTNER</h2><img alt="Lakeshore Shooting Sports League (fictional)" class="assosiates-icons" src="../icons/partner-fictional.svg">
		</aside>
	</header>
	<!-- The menu sits at the bottom of the page: style1.css puts the "navigaton" grid area last. -->
	<nav class="navigaton-bar" id="craNavbar">
	<?php
	echo nav_link('index.php', 'CRA', $here);
	echo nav_link('news.php', 'News', $here);
	echo nav_link('ranges.php', 'Ranges', $here);
	// Display links based upon the login status (same as Retrobaser):
	if (isset($_SESSION['user_id'])) {
		if ($_SESSION['user_level'] == 1) {
			echo nav_link('news_edit.php', 'Write News', $here);
		}
		echo nav_link('change_password.php', 'Change Password', $here);
		echo nav_link('logout.php', 'Log Out', $here);
	} else {
		echo nav_link('signup.php', 'Signup', $here);
		echo nav_link('login.php', 'Login', $here);
	}
	?>
		<a class="icon" href="javascript:void(0);" onclick="navFunction()">&#9776;</a>
	</nav>
