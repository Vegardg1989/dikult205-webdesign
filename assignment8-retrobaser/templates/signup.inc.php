<?php /* Done by Student 110 */

/* =======DEBUGING========*/

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
/*========LOGIN============*/



if (isset($_POST['register-account'])){

	require('../mysqli_connect.php');

    $username       = trim($_POST['username']);
    $email          = trim($_POST['email']);
    $password       = trim($_POST['password']);
    $password_repeat = trim($_POST['password_repeat']);

    //santizize data

    $username       = filter_var($username, FILTER_SANITIZE_STRING);
    $email          = filter_var($email, FILTER_SANITIZE_STRING);
    $password       = filter_var($password, FILTER_SANITIZE_STRING);
    $password_repeat = filter_var($password_repeat, FILTER_SANITIZE_STRING);



	if (empty($username) || empty($email) || empty($password) || empty($password_repeat)) {
        header("location: ../signup.php?error=emptyfields&username=" . $username . "&email=" . $email);
        exit();
    }
    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL) && !preg_match("/^[a-zA-Z0-9]*$/", $username)) {

    }
     elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header("location: ../signup.php?error=invalidemail" . $username);
        exit();
    } elseif (!preg_match("/^[a-zA-Z0-9]*$/", $username)) {
        header("location: ../signup.php?error=invalidusername" . $email);
        exit();
    }

        elseif ($password !== $password_repeat) {
        header("location: ../signup.php?error=passwordcheck&username" . $username . "&mail=" . $email);
        exit();
    } else {

        $sql  = "SELECT user_id FROM users WHERE username=?";
        $stmt = mysqli_stmt_init($conn);
        if (!mysqli_stmt_prepare($stmt, $sql)) {
            header("location: ../signup.php?error=sqlerror");
            exit();
        } else {
            mysqli_stmt_bind_param($stmt, "s", $username);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_store_result($stmt);
            $resultCheck = mysqli_stmt_num_rows($stmt);
            if ($resultCheck > 0) {
                header("location: ../signup.php?error=usernametaken&email".$email);
                exit();
            }
        else{}

        $sql  = "SELECT user_id FROM users WHERE email=?";
        $stmt = mysqli_stmt_init($conn);
        if (!mysqli_stmt_prepare($stmt, $sql)) {
            header("location: ../signup.php?error=sqlerror");
            exit();
        } else {
            mysqli_stmt_bind_param($stmt, "s", $email);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_store_result($stmt);
            $resultCheck = mysqli_stmt_num_rows($stmt);
            if ($resultCheck > 0) {
                header("location: ../signup.php?error=emailtaken&username".$username);
                exit();
            } else {
			// Create the activation code:
			$activation_code = md5(uniqid(rand(), true));
                $sql = "INSERT INTO users (username, email, pass, active, registration_date) VALUES (?,?,?,?, NOW() )";
                if (!mysqli_stmt_prepare($stmt, $sql)) {
                    header("location: ../signup.php?error=sqlerror");
                    exit();
                } else {
                    $hashed_pwd = password_hash($password, PASSWORD_DEFAULT);

                    mysqli_stmt_bind_param($stmt, "ssss", $username, $email, $hashed_pwd,$activation_code);
                    mysqli_stmt_execute($stmt);
				// Send the email:
				$body = "Thank you for registering at Retrobaser. To activate your account, please click on this link:\n\n";
				$body .= 'https://dikult205.k.uib.no/2020V/groups/gruppe3/assignment8/activate.php?a=' . urlencode($email) . "&b=$activation_code";
				mail($_POST['email'], 'Registration Confirmation', $body, 'From: admin@retrobaser.com');
                include('templates/footer.php'); // Include the HTML footer.
                header("location: ../signup.php?signup=success");
				exit();
			}
        }

	}
}
}   mysqli_stmt_close($stmt);
    mysqli_close($conn);
}else {
    header("location: ../signup.php");
    exit();
}
