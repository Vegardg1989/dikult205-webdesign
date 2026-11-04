<?php // Done by Student 108

/*==========POST COMMENT==========*/

function setComments($conn) {
    if (isset($_POST['commentSubmit'])) {
        $uid = mysqli_real_escape_string($conn, trim($_POST['uid']));
        $date = mysqli_real_escape_string($conn, trim($_POST['date']));

        if (empty($_POST['comment'])) {
            $errors[] = 'You forgot to put in text.';
        } else {
            $comment = mysqli_real_escape_string($conn, trim($_POST['comment']));
        }
                
        // Sanitizize data 
        
        $uid= filter_var($uid, FILTER_SANITIZE_STRING);
        $date= filter_var($date, FILTER_SANITIZE_STRING);
        $comment= filter_var($comment, FILTER_SANITIZE_STRING);


        if(empty($errors)){

            $sql = "INSERT INTO comments (uid, date, comment) VALUES ('$uid', '$date', '$comment')";
            $result = @mysqli_query($conn, $sql); // Run the query.
        }else {
            echo '<p>Error</p>';
        }

    }
}

/*==========GET COMMENT==========*/
    
function getComments($conn) {
    $sql = "SELECT * FROM comments ORDER BY date DESC"; // Select from comments in database and order by date DESC
    $result = $conn->query($sql); // Run the query.
    while ($row = $result->fetch_assoc()) {
        $id = $row['uid'];
        $sqlpart2 = "SELECT * FROM users WHERE user_id='$id'"; // Select from users in database and where the user_id is same as id.
        $resultpart2 = $conn->query($sqlpart2); // Run the query.
        if ($rowpart2 = $resultpart2->fetch_assoc()) {
            echo "<div class='commentBox'><p>";
            echo $rowpart2['username']."<br>"; // The username shown after post comment
            echo $row['date']."<br>"; // The date shown after post comment
            echo nl2br($row['comment']); // The comment itself shown after post comment
            echo "</p>";
            if (isset($_SESSION['user_id'])) {
                if ($_SESSION['user_id'] == $rowpart2['user_id'])
                    echo "<form class='commentEditButton' method='POST' action='editcomment.php'>
                        <input type='hidden' name='cid' value='".$row['cid']."'>
                        <input type='hidden' name='uid' value='".$row['uid']."'>
                        <input type='hidden' name='date' value='".$row['date']."'>
                        <input type='hidden' name='comment' value='".$row['comment']."'>
                        <button>Edit</button>
                    </form>";
            if (isset($_SESSION['user_id'])) {
                if ($_SESSION['user_level'] == 1)
                    echo "<form class='commentDeleteButton' method='POST' action='".deleteComments($conn)."'>
                        <input type='hidden' name='cid' value='".$row['cid']."'>
                        <button type='submit' name='commentDelete'>Delete</button>
                    </form>"; // The form for editing comment and delete button.
                }
            }
         echo "</div>";
        }
    }
}

/*==========EDIT COMMENT==========*/    
    
function editComments($conn) {
    if (isset($_POST['commentSubmit'])) {
        $cid = $_POST['cid'];
        $uid = $_POST['uid'];
        $date = $_POST['date'];
        $comment= $_POST['comment'];

        $sql = "UPDATE comments SET comment='$comment' WHERE cid='$cid'"; // Update comment
        $result = $conn->query($sql); // Run the query.
        header("Location: comments.php"); // Redirect to comments.php
    }
}

/*==========DELETE COMMENT==========*/
    
function deleteComments($conn) {
    if (isset($_POST['commentDelete'])) {
        $cid = $_POST['cid'];

        $sql = "DELETE FROM comments WHERE cid='$cid'"; // Delete comment
        $result = $conn->query($sql); // Run the query.
        header("Location: comments.php"); // Redirect to comments.php
    }
}

?>