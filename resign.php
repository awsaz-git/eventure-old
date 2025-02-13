<?php
session_start();
require 'connect.php';

$sql = "DELETE FROM `enrolled_event`
WHERE `event_id` = :event_id AND `enrolled_username` = :enrolled_username;";

$statement = $pdo->prepare($sql);
$event_id = $_POST['event_id'];
$statement->bindParam(":event_id", $event_id, PDO::PARAM_STR);
$statement->bindParam(":enrolled_username", $_SESSION['username'], PDO::PARAM_STR);
$statement->execute();

$sql = "UPDATE event
SET enrolledUsers = enrolledUsers - 1
WHERE id = :event_id;";

$statement = $pdo->prepare($sql);
$statement->bindParam(":event_id", $event_id, PDO::PARAM_STR);
$statement->execute();

header('location: profile.php');
exit();
