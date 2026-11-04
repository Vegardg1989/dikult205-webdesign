<?php /* Restored in 2026 by Vegard Gundersen with help from Claude (Anthropic's AI assistant).
 * The 2020 server code was lost; only the April 2020 rough draft survived. This file rebuilds the
 * submitted version from the June 2020 portfolio screenshots and reuses the Retrobaser login system. */
// Delete a news post after a yes/no confirmation, like Retrobaser's deletegames.php. Admins only.
$page_title = 'Delete News';
include('templates/header.php');
if (!isset($_SESSION['user_level']) || $_SESSION['user_level'] != 1) {
	ob_end_clean();
	header('Location: news.php?error=adminonly');
	exit();
}
require('mysqli_connect.php');
$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$sure = isset($_POST['sure']) ? $_POST['sure'] : 'No';
	if ($sure == 'Yes') {
		$stmt = mysqli_prepare($conn, 'DELETE FROM news WHERE news_id=? LIMIT 1');
		mysqli_stmt_bind_param($stmt, 'i', $id);
		mysqli_stmt_execute($stmt);
	}
	mysqli_close($conn);
	ob_end_clean();
	header('Location: news.php' . ($sure == 'Yes' ? '?done=deleted' : ''));
	exit();
}
$stmt = mysqli_prepare($conn, 'SELECT title FROM news WHERE news_id=?');
mysqli_stmt_bind_param($stmt, 'i', $id);
mysqli_stmt_execute($stmt);
mysqli_stmt_bind_result($stmt, $title);
mysqli_stmt_fetch($stmt);
?>
	<main class="content-landingpage">
		<h2>Delete a news post</h2>
		<p>Are you sure you want to delete <strong><?php echo h($title); ?></strong>?</p>
		<form action="news_delete.php?id=<?php echo $id; ?>" method="post" class="cra-form">
			<input type="radio" name="sure" value="Yes" id="yes"> <label for="yes">Yes</label>
			<input type="radio" name="sure" value="No" id="no" checked> <label for="no">No</label>
			<input type="submit" value="Submit">
		</form>
	</main>
	<aside class="aside-landingpage">
		<img alt="<?php echo isset($aside_alt) ? $aside_alt : 'A hunter aiming a rifle outdoors'; ?>" id="gunrange-landingpage" src="../pictures/<?php echo isset($aside_img) ? $aside_img : 'range2.jpg'; ?>">
		<p><q>A well regulated Militia, being necessary to the security of a free State,
		the right of the people to keep and bear Arms, shall not be infringed.</q></p><span>The Second Amendment</span>
	</aside>
<?php include('templates/footer.php'); ?>
