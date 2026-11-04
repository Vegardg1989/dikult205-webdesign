<?php /* Restored in 2026 by Vegard Gundersen with help from Claude (Anthropic's AI assistant).
 * The 2020 server code was lost; only the April 2020 rough draft survived. This file rebuilds the
 * submitted version from the June 2020 portfolio screenshots. */
if (!isset($page_title)) { $page_title = 'Home'; }
if (!isset($layout)) { $layout = ''; }
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta content="noindex,nofollow" name="robots">
	<meta content="noarchive" name="robots">
	<meta content="width=device-width, initial-scale=1" name="viewport">
	<link href="../css/style.css" rel="stylesheet" type="text/css">
	<link href="https://fonts.googleapis.com/css?family=Graduate&display=swap" rel="stylesheet">
	<title><?php echo htmlspecialchars($page_title); ?> · The Crime Lab Project</title>
</head>
<body class="container-landingpage w1 <?php echo $layout; ?>">
	<header>
		<a href="index.php"><img alt="The Crime Lab Project: a lab flask plus a data network" class="icon-header" src="../icons/crime-lab-logo.png"></a>
		<section class="header-title">
			<h1>LEVERAGING SCIENCE TO SAVE LIVES</h1>
		</section>
		<aside class="assosiates">
			<img alt="Parent Organization: Metro Science (fictional)" src="../icons/parent-org-fictional.svg">
		</aside>
	</header>
	<nav>
		<input id="menu-toggle" type="checkbox"> <label class="hamburger" for="menu-toggle"><img alt="Menu Icon" src="../icons/hamburger.svg"></label>
		<div class="nav-links">
			<a href="research.php">RESEARCH</a> <a href="crime_reduction.php">CRIME REDUCTION</a>
		</div>
	</nav>
