<?php
session_start();
require 'connect.php';

$sql = "INSERT INTO `enrolled_event`(`event_id`, `enrolled_username`)
    VALUES (:event_id,:enrolled_username)";

$statement = $pdo->prepare($sql);
$event_id = $_POST['event_id'];
$statement->bindParam(":event_id", $event_id, PDO::PARAM_STR);
$statement->bindParam(":enrolled_username", $_SESSION['username'], PDO::PARAM_STR);
$statement->execute();

$sql = "UPDATE event
SET enrolledUsers = enrolledUsers + 1
WHERE id = :event_id;";

$statement = $pdo->prepare($sql);
$statement->bindParam(":event_id", $event_id, PDO::PARAM_STR);
$statement->execute();

$sql = "SELECT * FROM event
WHERE id = :event_id;";

$statement = $pdo->prepare($sql);
$statement->bindParam(":event_id", $event_id, PDO::PARAM_STR);
$statement->execute();

$data = $statement->fetch();

$title = $data['title'];
$location = $data['location'];
$time = date("g:iA", strtotime($data['startTime']));
$date = explode('-', $data['date']);
$day = $date[2];
$month = strtoupper(date("M", mktime(0, 0, 0, $date[1])));
$organizer_id = $data['organizer_id'];

$sql = "SELECT 
            account_info.* 
        FROM 
            user 
        JOIN 
            account_info 
        ON 
            user.username = account_info.username 
        WHERE 
            user.organizer_id = :organizer_id";

$statement = $pdo->prepare($sql);
$statement->bindParam(":organizer_id", $organizer_id, PDO::PARAM_STR);
$statement->execute();

$data = $statement->fetch();

$organizer_email = $data['email'];
$organizer_name = $data['first_name'] . ' ' . $data['last_name'];

$to = $_SESSION['email'];
$subject = "🎉 Your Spot is Confirmed for " . $title;
$message = "Hi " . $_SESSION['first_name'] . ",

\n\nThank you for enrolling in " . $title . "! 🎊 We’re thrilled to have you join us. Here are the event details:

\nEvent Name: " . $title . "
\nDate: " . $month . ' ' . $day . "
\nTime: " . $time . "
\nLocation: " . $location . "
\nOrganizer: " . $organizer_name . "

\n\nWe’re excited to make this event a memorable experience for you. If you have any questions, feel free to reach out to the event organizer at " . $organizer_email . ".

\n\nSee you there!

\n\nCheers,
\nThe Eventure Team";
$headers = "From: eventurejo@gmail.com\r\n";

mail($to, $subject, $message, $headers);

header('location: profile.php');
exit();
