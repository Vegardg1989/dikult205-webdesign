<?php /* Restored in 2026 by Vegard Gundersen with help from Claude (Anthropic's AI assistant).
 * The 2020 server code was lost; only the April 2020 rough draft survived. This file rebuilds the
 * submitted version from the June 2020 portfolio screenshots and reuses the Retrobaser login system. */
$page_title = 'Home';
include('templates/header.php');
?>
	<main class="content-landingpage">
		<?php include('templates/messages.inc.php');
		if (isset($_SESSION['username'])) {
			echo '<p>Welcome ' . h($_SESSION['username']) . '</p>';
		} ?>
		<article>
			<h2>Welcome to the CRA</h2>
			<p>We are here to support and protect the rights and freedoms, which the
			amendments to the United States Constitution grant its citizens. Our goal is
			to preserve and protect the hunter's right to possess and use firearms for
			sporting purposes, and to educate the general public about rifle use.</p>
			<p>We strongly promote conservation and safe sport hunting as a wildlife
			management tool. We desire to see the highest degree of sportsmanship and
			good fellowship and to prevent the occurrence of unsporting behavior. We
			promote and assist in the planning, construction, and preservation of
			civilian shooting ranges.</p>
			<p>Gun violence is a serious public health problem, where thousands are
			murdered and harmed each year, including children, which is why our
			organization is dedicated to educating citizens about the lawful use and
			possession of firearms.</p>
			<p>We arrange safety courses for gun owners in the hope of averting such
			unfortunate incidents in the future.</p>
		</article>
	</main>
	<aside class="aside-landingpage">
		<img alt="<?php echo isset($aside_alt) ? $aside_alt : 'A hunter aiming a rifle outdoors'; ?>" id="gunrange-landingpage" src="../pictures/<?php echo isset($aside_img) ? $aside_img : 'range2.jpg'; ?>">
		<p><q>A well regulated Militia, being necessary to the security of a free State,
		the right of the people to keep and bear Arms, shall not be infringed.</q></p><span>The Second Amendment</span>
	</aside>
<?php include('templates/footer.php'); ?>
