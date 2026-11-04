<?php // Done by Student 108

    $page_title = 'Edit your comment';
    date_default_timezone_set('Europe/Oslo');
    require('mysqli_connect.php');
    require('templates/header.php');
    include 'comments.inc.php';
?>

<div class="container-comment-section">


<section class="comment-section">
    
<?php
    
$cid = $_POST['cid'];
$uid = $_POST['uid'];
$date = $_POST['date'];
$comment = $_POST['comment'];

echo "<form method='POST' action='".editComments($conn)."'>
        <input type='hidden' name='cid' value='".$cid."'>
        <input type='hidden' name='uid' value='".$uid."'>
        <input type='hidden' name='date' value='".$date."'>
        <textarea name='comment'>".$comment."</textarea><br>
        <button type='submit' class='commentButton' name='commentSubmit'>Edit</button>
      </form>"; // Form for editing comment
?>