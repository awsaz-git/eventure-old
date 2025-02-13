<?php
session_start();
require 'connect.php';

$sql = "DELETE FROM `event` WHERE id = :event_id";

$statement = $pdo->prepare($sql);
$event_id = $_POST['event_id'];
$statement->bindParam(":event_id", $event_id, PDO::PARAM_STR);
$statement->execute();

header('location: profile.php');
exit();
