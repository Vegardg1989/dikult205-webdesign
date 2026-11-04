<?php /* Restored in 2026 by Vegard Gundersen with help from Claude (Anthropic's AI assistant).
 * The 2020 server code was lost; only the April 2020 rough draft survived. This file rebuilds the
 * submitted version from the June 2020 portfolio screenshots and reuses the Retrobaser login system. */
// Headline news. The rough draft only had a placeholder post; admins can now add, edit and delete.
$page_title = 'News';
include('templates/header.php');
require('mysqli_connect.php');
$result = mysqli_query($conn, 'SELECT n.news_id, n.title, n.body, n.image, n.created, u.username
	FROM news n LEFT JOIN users u ON u.user_id = n.user_id ORDER BY n.created DESC');
$is_admin = isset($_SESSION['user_level']) && $_SESSION['user_level'] == 1;
?>
	<main class="content-landingpage">
		<h2>Headline News</h2>
		<?php include('templates/messages.inc.php');
		if ($is_admin) {
			echo '<p><a href="news_edit.php">+ Write a news post</a></p>';
		}
		if (mysqli_num_rows($result) == 0) {
			echo '<p>No news yet.</p>';
		}
		while ($row = mysqli_fetch_assoc($result)) {
			echo '<section class="media">';
			echo '<h3 class="title">' . h($row['title']) . '</h3>';
			echo '<p class="news-meta">' . date('F j, Y', strtotime($row['created'])) . ($row['username'] ? ' · ' . h($row['username']) : '') . '</p>';
			if ($row['image'] != '') {
				echo '<img class="img" src="../pictures/' . h($row['image']) . '" alt="">';
			}
			echo '<div class="content">' . nl2br(h($row['body'])) . '</div>';
			if ($is_admin) {
				echo '<p><a href="news_edit.php?id=' . $row['news_id'] . '">Edit</a> <a href="news_delete.php?id=' . $row['news_id'] . '">Delete</a></p>';
			}
			echo '</section>';
		}
		mysqli_close($conn);
		?>
	</main>
<?php $aside_img = 'cons.jpg'; $aside_alt = 'We the People from the U.S. Constitution under a magnifying glass'; ?>
	<aside class="aside-landingpage">
		<img alt="<?php echo isset($aside_alt) ? $aside_alt : 'A hunter aiming a rifle outdoors'; ?>" id="gunrange-landingpage" src="../pictures/<?php echo isset($aside_img) ? $aside_img : 'range2.jpg'; ?>">
		<p><q>A well regulated Militia, being necessary to the security of a free State,
		the right of the people to keep and bear Arms, shall not be infringed.</q></p><span>The Second Amendment</span>
	</aside>
<?php include('templates/footer.php'); ?>
