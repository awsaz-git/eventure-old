<?php
require 'connect.php';

$role = 'individual';

if (isset($_POST['companyName']) && isset($_POST['address']) && isset($_POST['verificationFile'])) {
    $role = 'organizer';
}

function isInUse($pdo)
{
    $sql = "SELECT COUNT(*) FROM user WHERE username=:username";

    $statement = $pdo->prepare($sql);
    $username = $_POST['username'];
    $statement->bindParam(":username", $username, PDO::PARAM_STR);
    $statement->execute();

    $data = $statement->fetch();

    if ($data == 0) {
        return 1;
    }
    $sql = "SELECT COUNT(*) FROM account_info WHERE email=:email";

    $statement = $pdo->prepare($sql);
    $email = $_POST['email'];
    $statement->bindParam(":email", $email, PDO::PARAM_STR);
    $statement->execute();

    $data = $statement->fetch();

    if ($data == 0) {
        return 2;
    }
    $sql = "SELECT COUNT(*) FROM account_info WHERE phone_number=:phone_number";

    $statement = $pdo->prepare($sql);
    $phone_number = $_POST['phoneNumber'];
    $statement->bindParam(":phone_number", $phone_number, PDO::PARAM_STR);
    $statement->execute();

    $data = $statement->fetch();

    if ($data == 0) {
        return 3;
    }

    return 0;
}

$error = isInUse($pdo);
if ($error > 0) {
    if ($role == 'organizer') {
        header('Location: signup-organizer.php?error=' . (string)$error);
        exit();
    } else {
        header('Location: signup.php?error=' . (string)$error);
        exit();
    }
}

$hashedPassword = password_hash($_POST['password'], PASSWORD_ARGON2I);

if ($role == 'organizer') {
    $sql = "INSERT INTO `organizer`(`company_name`, `address_line`, `verification_file`)
    VALUES (:company_name,:address_line,:verification_file)";

    $statement = $pdo->prepare($sql);
    $companyName = $_POST['companyName'];
    $address = $_POST['address'];
    $verificationFile = $_POST['verificationFile'];
    $statement->bindParam(":company_name", $companyName, PDO::PARAM_STR);
    $statement->bindParam(":address_line", $address, PDO::PARAM_STR);
    $statement->bindParam(":verification_file", $verificationFile, PDO::PARAM_STR);
    $statement->execute();

    if (isset($_FILES['verificationFile'])) {
        if (move_uploaded_file($_FILES['verificationFile']['tmp_name'], "uploaded/verification-file/" . $_FILES['verificationFile']['name'])) {
            echo 'console.log("uploaded successfully");';
        }
    }

    $sql = "SELECT * FROM organizer ORDER BY organizer_id DESC LIMIT 1";

    $statement = $pdo->prepare($sql);
    $statement->execute();

    $data = $statement->fetch();

    $sql = "INSERT INTO `user`(`username`, `password`, `role`, `organizer_id`)
    VALUES (:username,:password,:role,:organizer_id)";

    $statement = $pdo->prepare($sql);
    $username = $_POST['username'];
    $password = $hashedPassword;
    $organizer_id = $data['organizer_id'];
    $statement->bindParam(":username", $username, PDO::PARAM_STR);
    $statement->bindParam(":password", $password, PDO::PARAM_STR);
    $statement->bindParam(":role", $role, PDO::PARAM_STR);
    $statement->bindParam(":organizer_id", $organizer_id, PDO::PARAM_STR);
    $statement->execute();

    $sql = "INSERT INTO `account_info`(`username`, `email`, `phone_number`, `first_name`, `last_name`, `date_of_birth`, `gender`)
    VALUES (:username,:email,:phone_number,:first_name,:last_name,:date_of_birth,:gender)";

    $statement = $pdo->prepare($sql);
    $email = $_POST['email'];
    $phone_number = $_POST['phoneNumber'];
    $first_name = $_POST['firstName'];
    $last_name = $_POST['lastName'];
    $date_of_birth = $_POST['dateOfBirth'];
    $gender = $_POST['gender'];
    $statement->bindParam(":username", $username, PDO::PARAM_STR);
    $statement->bindParam(":email", $email, PDO::PARAM_STR);
    $statement->bindParam(":phone_number", $phone_number, PDO::PARAM_STR);
    $statement->bindParam(":first_name", $first_name, PDO::PARAM_STR);
    $statement->bindParam(":last_name", $last_name, PDO::PARAM_STR);
    $statement->bindParam(":date_of_birth", $date_of_birth, PDO::PARAM_STR);
    $statement->bindParam(":gender", $gender, PDO::PARAM_STR);
    $statement->execute();

    $pdo = null;

    header('Location: login.php?signup=successful');
    exit();
} else {
    $sql = "INSERT INTO `user`(`username`, `password`, `role`)
    VALUES (:username,:password,:role)";

    $statement = $pdo->prepare($sql);
    $username = $_POST['username'];
    $password = $hashedPassword;
    $statement->bindParam(":username", $username, PDO::PARAM_STR);
    $statement->bindParam(":password", $password, PDO::PARAM_STR);
    $statement->bindParam(":role", $role, PDO::PARAM_STR);
    $statement->execute();

    $sql = "INSERT INTO `account_info`(`username`, `email`, `phone_number`, `first_name`, `last_name`, `date_of_birth`, `gender`)
    VALUES (:username,:email,:phone_number,:first_name,:last_name,:date_of_birth,:gender)";

    $statement = $pdo->prepare($sql);
    $email = $_POST['email'];
    $phone_number = $_POST['phoneNumber'];
    $first_name = $_POST['firstName'];
    $last_name = $_POST['lastName'];
    $date_of_birth = $_POST['dateOfBirth'];
    $gender = $_POST['gender'];
    $statement->bindParam(":username", $username, PDO::PARAM_STR);
    $statement->bindParam(":email", $email, PDO::PARAM_STR);
    $statement->bindParam(":phone_number", $phone_number, PDO::PARAM_STR);
    $statement->bindParam(":first_name", $first_name, PDO::PARAM_STR);
    $statement->bindParam(":last_name", $last_name, PDO::PARAM_STR);
    $statement->bindParam(":date_of_birth", $date_of_birth, PDO::PARAM_STR);
    $statement->bindParam(":gender", $gender, PDO::PARAM_STR);
    $statement->execute();

    $pdo = null;
    if ($role == 'organizer') {
        header('Location: login.php?error=0' . (string)$error);
        exit();
    } else {
        header('Location: login.php?error=' . (string)$error);
        exit();
    }
}



/* echo print_r($_POST);

echo $_POST['firstName'] . "<br>";
echo $_POST['lastName'] . "<br>";
echo $_POST['phoneNumber'] . "<br>";
echo $_POST['dateOfBirth'] . "<br>";
echo $_POST['gender'] . "<br>";
echo $_POST['email'] . "<br>";
echo $_POST['username'] . "<br>";
echo $_POST['password'] . "<br>";

if (isset($_POST['companyName']) && isset($_POST['address']) && isset($_POST['verificationFile'])) {
    echo $_POST['companyName'];
    echo $_POST['address'];
    echo $_POST['verificationFile'];
}
echo $role; */
