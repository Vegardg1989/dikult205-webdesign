<?php /* Restored in 2026 by Vegard Gundersen with help from Claude (Anthropic's AI assistant).
 * The 2020 server code was lost; only the April 2020 rough draft survived. This file rebuilds the
 * submitted version from the June 2020 portfolio screenshots and reuses the Retrobaser login system. */
// Add a news post (no id) or edit one (?id=). Admins only.
$page_title = 'Write News';
include('templates/header.php');
if (!isset($_SESSION['user_level']) || $_SESSION['user_level'] != 1) {
	ob_end_clean();
	header('Location: news.php?error=adminonly');
	exit();
}
require('mysqli_connect.php');
$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$pictures = array('', 'range1.jpg', 'range2.jpg', 'cons.jpg', 'eagle1.jpg');
$title = $body = $image = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$title = trim($_POST['title']);
	$body = trim($_POST['body']);
	$image = in_array($_POST['image'], $pictures, true) ? $_POST['image'] : '';
	if ($title != '' && $body != '') {
		if ($id) {
			$stmt = mysqli_prepare($conn, 'UPDATE news SET title=?, body=?, image=? WHERE news_id=? LIMIT 1');
			mysqli_stmt_bind_param($stmt, 'sssi', $title, $body, $image, $id);
		} else {
			$stmt = mysqli_prepare($conn, 'INSERT INTO news (user_id, title, body, image) VALUES (?, ?, ?, ?)');
			mysqli_stmt_bind_param($stmt, 'isss', $_SESSION['user_id'], $title, $body, $image);
		}
		mysqli_stmt_execute($stmt);
		mysqli_close($conn);
		ob_end_clean();
		header('Location: news.php?done=saved');
		exit();
	}
	$error = 'emptyfields';
} elseif ($id) {
	$stmt = mysqli_prepare($conn, 'SELECT title, body, image FROM news WHERE news_id=?');
	mysqli_stmt_bind_param($stmt, 'i', $id);
	mysqli_stmt_execute($stmt);
	mysqli_stmt_bind_result($stmt, $title, $body, $image);
	mysqli_stmt_fetch($stmt);
}
?>
	<main class="content-landingpage">
		<h2><?php echo $id ? 'Edit the news post' : 'Write a news post'; ?></h2>
		<?php if (isset($error)) echo '<p class="error">Fill in both the title and the text.</p>'; ?>
		<form action="news_edit.php<?php echo $id ? '?id=' . $id : ''; ?>" method="post" class="cra-form">
			<label for="title">Title:</label>
			<input type="text" name="title" id="title" value="<?php echo h($title); ?>">
			<label for="body">Text:</label>
			<textarea name="body" id="body" rows="10"><?php echo h($body); ?></textarea>
			<label for="image">Picture:</label>
			<select name="image" id="image">
			<?php foreach ($pictures as $p) {
				$sel = ($p == $image) ? ' selected' : '';
				echo '<option value="' . $p . '"' . $sel . '>' . ($p == '' ? 'No picture' : $p) . '</option>';
			} ?>
			</select>
			<input type="submit" value="Save">
		</form>
	</main>
	<aside class="aside-landingpage">
		<img alt="<?php echo isset($aside_alt) ? $aside_alt : 'A hunter aiming a rifle outdoors'; ?>" id="gunrange-landingpage" src="../pictures/<?php echo isset($aside_img) ? $aside_img : 'range2.jpg'; ?>">
		<p><q>A well regulated Militia, being necessary to the security of a free State,
		the right of the people to keep and bear Arms, shall not be infringed.</q></p><span>The Second Amendment</span>
	</aside>
<?php include('templates/footer.php'); ?>
