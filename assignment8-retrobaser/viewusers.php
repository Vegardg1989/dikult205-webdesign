<?php /* Done by Student 110 */ 

$page_title = 'View the Current Users';
require 'templates/header.php';

if ($_SESSION['user_level'] == 0) {
    
    $url = 'https://dikult205.k.uib.no/2020V/groups/gruppe3/assignment8/index.php'; // Define the URL.
    ob_end_clean(); // Delete the buffer.
    header("Location: $url");
    exit(); // Quit the script.   
}

echo 	'<main class="container-viewusers">';
echo	'<section class="content-viewusers">';
echo	'<h1>Current Users</h1>';


require 'mysqli_connect.php';

// Number of records to show per page:
$display = 5;

// Determine how many pages there are...
if (isset($_GET['p']) && is_numeric($_GET['p'])) { // Already been determined.
	$pages = $_GET['p'];
} else { // Need to determine.
 	// Count the number of records:
	$q = "SELECT COUNT(user_id) FROM users";
	$r = @mysqli_query($conn, $q);
	$row = @mysqli_fetch_array($r, MYSQLI_NUM);
	$records = $row[0];
	// Calculate the number of pages...
	if ($records > $display) { // More than 1 page.
		$pages = ceil ($records/$display);
	} else {
		$pages = 1;
	}
} // End of p IF.

// Determine where in the database to start returning results...
if (isset($_GET['s']) && is_numeric($_GET['s'])) {
	$start = $_GET['s'];
} else {
	$start = 0;
}

// Determine the sort...
// Default is by registration date.
$sort = (isset($_GET['sort'])) ? $_GET['sort'] : 'rd';

// Determine the sorting order:
switch ($sort) {
	case 'un':
		$order_by = 'username ASC';
		break;
	case 'em':
		$order_by = 'email ASC';
		break;
	case 'rd':
		$order_by = 'registration_date ASC';
		break;
	default:
		$order_by = 'registration_date ASC';
		$sort = 'rd';
		break;
}

// Define the query:
$q = "SELECT username, email, DATE_FORMAT(registration_date, '%M %d, %Y') AS dr, user_id FROM users ORDER BY $order_by LIMIT $start, $display";
$r = @mysqli_query($conn, $q); // Run the query.


echo '<table>
<thead>
<tr>
	<th scope="col"><a href="viewusers.php?sort=un">Username</a></th>
	<th scope="col"><a href="viewusers.php?sort=rd">Registration Date</a></th>
	<th scope="col">Edit User</th>
	<th scope="col">Delete User</th>
</tr>
</thead>
<tbody>
';

// Fetch and print all the records....
$bg = '#eeeeee';
while ($row = mysqli_fetch_array($r, MYSQLI_ASSOC)) {
	$bg = ($bg=='#eeeeee' ? '#ffffff' : '#eeeeee');
		echo'
		<tr bgcolor="' . $bg . '">
		<td scope="row" data-label="UserName">'.$row['username'].'</td>
		<td scope="row" data-label="Registration">'.$row['dr']. '</td>
		<td scope="row" data-label="Edit"><a href="edituser.php?id='.$row['user_id'].'">Edit</a></td>
		<td scope="row" data-label="Delete"><a href="deleteuser.php?id='.$row['user_id'].'">Delete</a></td>

	</tr>
	';
} // End of WHILE loop.
echo '</tbody></table>';

mysqli_free_result($r);
mysqli_close($conn);

// Make the links to other pages, if necessary.
if ($pages > 1) {

	echo '<br><p>';
	$current_page = ($start/$display) + 1;

	// If it's not the first page, make a Previous button:
	if ($current_page != 1) {
		echo '<a href="viewusers.php?s=' . ($start - $display) . '&p=' . $pages . '&sort=' . $sort . '">Previous</a> ';
	}

	// Make all the numbered pages:
	for ($i = 1; $i <= $pages; $i++) {
		if ($i != $current_page) {
			echo '<a href="viewusers.php?s=' . (($display * ($i - 1))) . '&p=' . $pages . '&sort=' . $sort . '">' . $i . '</a> ';
		} else {
			echo $i . ' ';
		}
	} // End of FOR loop.

	// If it's not the last page, make a Next button:
	if ($current_page != $pages) {
		echo '<a href="viewusers.php?s=' . ($start + $display) . '&p=' . $pages . '&sort=' . $sort . '">Next</a>';
	}

	echo '</p>'; // Close the paragraph.

} // End of links section.

?>
</section>
</main>

<?php include('templates/footer.php');?>