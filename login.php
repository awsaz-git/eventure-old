<?php
session_start();

if (isset($_SESSION['username'])) {
    header('location: profile.php');
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles/login.css">
    <link rel="stylesheet" href="styles/font.css">
    <link rel="stylesheet" href="styles/elements.css">
    <link rel="icon" type="image/png" href="assets/images/icon.png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,100..1000;1,9..40,100..1000&display=swap" rel="stylesheet">
    <title>Eventure</title>
</head>

<body>
    <section class="parent">
        <img src="assets/images/amman2.jpg" id="backgroundImage">
        <div class="content" id="content">
            <div class="welcomeText">Welcome Back!</div>
            <form class="loginForm" action="login.php" method="post" id="loginForm">
                <section>
                    <label class="label" id="usernameLabel" for="username">username</label>
                    <br>
                    <input class="textField2" id="username" type="text" name="username">
                </section>
                <section class="passwordField">
                    <label class="label" id="passwordLabel" for="password">password</label>
                    <br>
                    <input class="textField2" id="password" type="password" name="password">
                </section>
            </form>
            <a href="" class="forgotPassword" id="forgotPassword">Forgot password?</a>
            <button class="buttonType1" id="loginButton" for="loginForm">Login</button>
            <section class="signupSection">
                <div style="color: #3b3b3b;">Don't Have an account?</div>
                <a href="signup.php" style="color: #242565; font-weight: bold;">&nbsp;Sign up</a>
            </section>
        </div>

    </section>
    <script>
        document.querySelector('#loginButton').addEventListener("click", function() {
            document.querySelector('#loginForm').submit();
        });
    </script>
</body>

</html>

<?php
require 'connect.php';

if (isset($_POST['username']) && isset($_POST['password'])) {
    $sql = "SELECT * FROM user WHERE username=:username";
    $statement = $pdo->prepare($sql);
    $username = $_POST['username'];
    $statement->bindParam(":username", $username, PDO::PARAM_STR);
    $statement->execute();

    $count = $statement->rowCount();

    if ($count == 1) {
        $sql = "SELECT password FROM user WHERE username=:username";
        $statement = $pdo->prepare($sql);
        $statement->bindParam(":username", $username, PDO::PARAM_STR);
        $statement->execute();

        $data = $statement->fetch();

        if (password_verify($_POST['password'], $data['password'])) {
            $_SESSION['username'] = $username;

            $sql = "SELECT 
        user.role,
        account_info.email,
        account_info.phone_number,
        account_info.first_name,
        account_info.last_name,
        account_info.date_of_birth,
        account_info.profile_image,
        account_info.gender
    FROM 
        user
    JOIN 
        account_info
    ON 
        user.username = account_info.username
    WHERE 
        user.username = :username
    ";

            $statement = $pdo->prepare($sql);
            $statement->bindParam(":username", $username, PDO::PARAM_STR);
            $statement->execute();

            $data = $statement->fetch();
            $_SESSION['role'] = $data['role'];
            $_SESSION['email'] = $data['email'];
            $_SESSION['phone_number'] = $data['phone_number'];
            $_SESSION['first_name'] = $data['first_name'];
            $_SESSION['last_name'] = $data['last_name'];
            $_SESSION['date_of_birth'] = $data['date_of_birth'];
            $_SESSION['gender'] = $data['gender'];



            if ($_SESSION['role'] == 'organizer') {
                $sql = "SELECT * FROM user WHERE username=:username";
                $statement = $pdo->prepare($sql);
                $statement->bindParam(":username", $username, PDO::PARAM_STR);
                $statement->execute();
                $data = $statement->fetch();
                $_SESSION['organizer_id'] = $data['organizer_id'];
            }

            header("location:index.php");
        } else {
            echo "<script>
    window.alert('username or password incorrect');
</script>";
        }
    } else {
        echo "<script>
    window.alert('username or password incorrect');
</script>";
    }
}

$pdo = null;

?>