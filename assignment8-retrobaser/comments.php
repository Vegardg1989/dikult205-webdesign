<?php // Done by Student 108
    
    $page_title = 'Discuss/Suggest Retro Games';
    date_default_timezone_set('Europe/Oslo');
    require('mysqli_connect.php');
    require('templates/header.php');
    include 'comments.inc.php';
?>

<div class="container-comment-section">


<div class="comment-section">

<?php
/*==========COMMENT FORM==========*/

if (isset($_SESSION['user_id'])) {
    echo "<form method='POST' action='".setComments($conn)."'>
        <input type='hidden' name='uid' value='".$_SESSION['user_id']."'>
        <input type='hidden' name='date' value='".date('Y-m-d H:i:s')."'>
        <textarea name='comment'></textarea><br>
        <button type='submit' class='commentButton' name='commentSubmit'>Post a comment</button>
    </form>"; // If logged in show form for posting comment
} else {
    echo "<p>If you want to take part in the discussion or give your suggestion on a retro game, please sign up and log in.</p>"; // If not logged in show this
}

getComments($conn);
echo'</div>';

?>

</div>

<?php include('templates/footer.php'); ?>