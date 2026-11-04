<?php /* Restored in 2026 by Vegard Gundersen with help from Claude (Anthropic's AI assistant).
 * The 2020 server code was lost; only the April 2020 rough draft survived. This file rebuilds the
 * submitted version from the June 2020 portfolio screenshots and reuses the Retrobaser login system. */
// Gun ranges that members rate from one to five stars (one rating per member, can be changed).
$page_title = 'Ranges';
include('templates/header.php');
require('mysqli_connect.php');
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	ob_end_clean();
	if (!isset($_SESSION['user_id'])) {
		header('Location: login.php?error=loginfirst');
		exit();
	}
	$range_id = (int) $_POST['range_id'];
	$stars = (int) $_POST['stars'];
	if ($stars >= 1 && $stars <= 5) {
		$stmt = mysqli_prepare($conn, 'INSERT INTO range_ratings (user_id, range_id, stars) VALUES (?, ?, ?)
			ON DUPLICATE KEY UPDATE stars = VALUES(stars)');
		mysqli_stmt_bind_param($stmt, 'iii', $_SESSION['user_id'], $range_id, $stars);
		mysqli_stmt_execute($stmt);
	}
	header('Location: ranges.php?done=rated');
	exit();
}
$result = mysqli_query($conn, 'SELECT r.range_id, r.name, r.location, r.description,
	AVG(rr.stars) AS avg_stars, COUNT(rr.stars) AS votes
	FROM ranges r LEFT JOIN range_ratings rr ON rr.range_id = r.range_id
	GROUP BY r.range_id ORDER BY avg_stars DESC');
?>
	<main class="content-landingpage">
		<h2>Gun Ranges</h2>
		<?php include('templates/messages.inc.php');
		while ($row = mysqli_fetch_assoc($result)) {
			$avg = $row['votes'] ? round($row['avg_stars'], 1) : 0;
			echo '<section class="range">';
			echo '<h3>' . h($row['name']) . '</h3><p>' . h($row['location']) . '</p><p>' . h($row['description']) . '</p>';
			echo '<p>' . str_repeat('&#9733;', (int) round($avg)) . str_repeat('&#9734;', 5 - (int) round($avg)) . " $avg / 5 ({$row['votes']} ratings)</p>";
			if (isset($_SESSION['user_id'])) {
				echo '<form action="ranges.php" method="post"><input type="hidden" name="range_id" value="' . $row['range_id'] . '">';
				for ($i = 1; $i <= 5; $i++) {
					echo '<button type="submit" name="stars" value="' . $i . '">' . $i . '&#9733;</button> ';
				}
				echo '</form>';
			}
			echo '</section>';
		}
		if (!isset($_SESSION['user_id'])) {
			echo '<p>Log in as a member to rate the ranges.</p>';
		}
		mysqli_close($conn);
		?>
	</main>
	<aside class="aside-landingpage">
		<img alt="<?php echo isset($aside_alt) ? $aside_alt : 'A hunter aiming a rifle outdoors'; ?>" id="gunrange-landingpage" src="../pictures/<?php echo isset($aside_img) ? $aside_img : 'range2.jpg'; ?>">
		<p><q>A well regulated Militia, being necessary to the security of a free State,
		the right of the people to keep and bear Arms, shall not be infringed.</q></p><span>The Second Amendment</span>
	</aside>
<?php include('templates/footer.php'); ?>
