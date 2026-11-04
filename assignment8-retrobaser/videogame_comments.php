<?php /* Done by Student 108 */

/*==========EDIT COMMENT==========*/    
    
function editComments($conn) {
    if (isset($_POST['commentSubmit'])) {
        $cid = $_POST['cid'];
        $uid = $_POST['uid'];
        $date = $_POST['date'];
        $comment= $_POST['comment'];

        $sql = "UPDATE videogame_comments SET comment='$comment' WHERE cid='$cid'";
        $result = $conn->query($sql);
        header("Location: games.php");
    }
}

/*==========DELETE COMMENT==========*/
    
function deleteComments($conn) {
    if (isset($_POST['commentDelete'])) {
        $cid = $_POST['cid'];

        $sql = "DELETE FROM videogame_comments WHERE cid='$cid'";
        $result = $conn->query($sql);
        header("Location: ../games.php");
    }
}

?>