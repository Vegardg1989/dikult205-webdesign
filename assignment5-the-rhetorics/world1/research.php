<?php /* Restored in 2026 by Vegard Gundersen with help from Claude (Anthropic's AI assistant).
 * The 2020 server code was lost; only the April 2020 rough draft survived. This file rebuilds the
 * submitted version from the June 2020 portfolio screenshots. */
// The draft had placeholder text here; the content follows the submitted version.
$page_title = 'Research';
include('templates/header.php');
?>
	<main class="content-landingpage">
		<article>
			<h2>A public health perspective on gun violence.</h2>
			<p>Gun violence is a serious public health problem. Thousands are killed and harmed each year,
			children among them, as a recent story in the Chicago Tribune shows: a toddler was severely wounded
			as his parents struggled over a gun.</p>
			<h2>Jobs change outcomes.</h2>
			<p>In a randomised study of a Chicago summer jobs programme, teenagers who were offered a job were
			43% less likely to be arrested for a violent crime than those who were not. Data like this is why we
			argue that jobs for young people are violence prevention.</p>
			<h2>Heat and shootings.</h2>
			<p>Shootings in Chicago peak on hot summer days. Why is still heatedly debated: some research
			suggests heat makes people more irritable and quicker to anger. We report the pattern and do not
			claim more than the data shows.</p>
			<?php
			// Shooting victims per day by weather (figures from the chart used in 2020, redrawn as HTML).
			$weather = array('Hot' => 13.0, 'Pleasant' => 10.2, 'Cold' => 6.3);
			$max = max($weather);
			?>
			<div class="chart" role="img" aria-label="Shooting victims per day in Chicago: hot days 13.0, pleasant days 10.2, cold days 6.3">
				<h3>Hot days particularly dangerous in Chicago</h3>
				<p class="sub">Shooting victims per day, by weather</p>
				<?php foreach ($weather as $label => $value) {
					$width = round($value / $max * 100);
					echo '<div class="row"><span>' . $label . '</span><span class="bar" style="width: ' . $width . '%">' . number_format($value, 1) . '</span></div>';
				} ?>
				<p class="src">Hot is 85 &deg;F and up, pleasant 50 to 84 &deg;F, cold under 50 &deg;F.</p>
			</div>
		</article>
	</main>
	<aside class="aside-landingpage">
		<img alt="Chart: U.S. gun deaths rose from 28,874 in 1999 to 39,773 in 2017" id="image-landingpage" src="../pictures/gunstats.jpeg">
		<ul>
			<li>Data from the Centers for Disease Control and Prevention show that 39,773 people died from
			gunshot wounds in the United States in 2017. Chart: Statista (CC BY-ND).</li>
		</ul>
	</aside>
<?php include('templates/footer.php'); ?>
