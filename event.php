<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("location:login.php");
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles/event.css">
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
        <?php
        require 'connect.php';

        $id = $_GET['id'];

        $sql = "SELECT * FROM event WHERE id = " . $id;

        $statement = $pdo->prepare($sql);
        $statement->execute();

        $data = $statement->fetchAll();

        foreach ($data as $event) {
            if ($event['image'] == null) {
                $image = "assets/images/amman.png";
            } else {
                $image = $event['image'];
            }
            echo '<img src="/assets/images/rainbowst.jpg" alt="image not available" id="backgroundImage">';
        }
        ?>
        <div class="content">
            <h2>Registration</h2>
            <?php
            require 'connect.php';

            $id = $_GET['id'];


            $sql = "SELECT * FROM event WHERE id = " . $id;

            $statement = $pdo->prepare($sql);
            $statement->execute();

            $event = $statement->fetch();

            $date = explode('-', $event['date']);
            $day = $date[2];
            $month = strtoupper(date("M", mktime(0, 0, 0, $date[1])));
            $startTime = date("g:iA", strtotime($event['startTime']));
            $endTime = date("g:iA", strtotime($event['endTime']));

            echo '
            <section class="wideScreen">

            <div class="eventInfo">
                <div class="eventTitle">' . $event['title'] . '</div>
                <div class="eventDescription"> ' . nl2br($event['description']) . '</div>
            </div>

            <section class="rightSide">
            <img src="' . $event['image'] . '" class="image">
            </img>
            <div class="eventInfo2">
            <section>
            <section class="eventInfoTop">
                    <img src="assets/icons/calendar.png" class="icon">
                    <div class="date">' . $month . ' ' . $day . '</div>
                    <img src="assets/icons/ticket.png" class="icon">
                    <div class="fee">' . $event['fee'] . ' JD' . '</div>
                </section>
                <section class="eventInfoBottom">
                    <img src="assets/icons/clock.png" class="icon">
                    <div class="time">' . $startTime . ' - ' .  $endTime . '</div>
                </section>
            </section>
            <section>
            <section class="eventInfoBottom">
            <img src="assets/icons/location.png" class="icon">
            <a href="' . $event['location'] . '">
            <div class="time">' . ucfirst($event['city']) . '</div>
            </a>
            </section>
            <div class="eventInfoBottom dropdown">
            <img src="assets/icons/users.png" class="icon">
            <div class="size">' . $event['enrolledUsers'] . '/' . $event['size'] . '</div>
            <div class="dropdownMenu">
                ';


            $sql = " 
    SELECT 
    account_info.first_name, 
    account_info.last_name, 
    account_info.profile_image
FROM 
    enrolled_event
INNER JOIN 
    account_info 
ON 
    enrolled_event.enrolled_username = account_info.username
WHERE 
    enrolled_event.event_id = :event_id;
";

            $statement = $pdo->prepare($sql);
            $statement->bindParam(":event_id", $id, PDO::PARAM_STR);
            $statement->execute();

            $data = $statement->fetchAll();

            foreach ($data as $user) {
                if (isset($user['profile_image'])) {
                    $image = $user['profile_image'];
                } else {
                    $image = "/assets/profile_images/generic.png";
                }
                echo '
                <section class="dropdownItem">
                <img src="' . $image . '" class="profileImage">
                <div class="name">' . $user['first_name'] . ' ' . $user['last_name'] . '</div>
                </section>
                ';
            }


            echo '
                
            </div>
            </div>
            </section>
            

            </section>
            </section>
            ';

            $sql = "SELECT username FROM user WHERE organizer_id = :organizer_id";

            $statement = $pdo->prepare($sql);
            $statement->bindParam(":organizer_id", $event['organizer_id'], PDO::PARAM_STR);
            $statement->execute();

            $data = $statement->fetch();
            $organizerUsername = $data['username'];

            $sql = "SELECT * FROM account_info WHERE username = :username";
            $statement = $pdo->prepare($sql);
            $statement->bindParam(":username", $organizerUsername, PDO::PARAM_STR);
            $statement->execute();

            $data = $statement->fetch();

            $sql = "SELECT company_name FROM organizer WHERE organizer_id = :organizer_id";
            $statement = $pdo->prepare($sql);
            $statement->bindParam(":organizer_id", $event['organizer_id'], PDO::PARAM_STR);
            $statement->execute();

            $organizer = $statement->fetch();

            if (isset($data['profile_image'])) {
                $image = $data['profile_image'];
            } else {
                $image = "/assets/profile_images/generic.png";
            }

            echo '
            <section class="row">
                <section class="organizerSection">
                <img src="' . $image . '" class="profileImage">
                <section>
                <div class="subtitle">organizer:</div>
                <div class="name">' . $organizer['company_name'] . '</div>
                </section>
                </section>
            
                <section class="column">
                ';

            $sql = "SELECT * FROM enrolled_event WHERE enrolled_username = :username AND event_id = :event_id";

            $statement = $pdo->prepare($sql);
            $statement->bindParam(":username", $_SESSION['username'], PDO::PARAM_STR);
            $statement->bindParam(":event_id", $id, PDO::PARAM_STR);
            $statement->execute();

            $count = $statement->rowCount();


            if (isExpired($event)) {
                echo '
            <button class="buttonType1" id="enrollButton" style="background-color:#D3D3D3;">Event Ended</button>
            ';
            } else {
                if ($count == 1) {

                    echo '
                    <form action="resign.php" method="post" id="enroll">
            <input  type="hidden" name="event_id" value="' . $id . '"></input>
            <button class="buttonType1" type="submit" id="enrollButton" style="background-color:#c00000;" for="enroll">Resign</button>
            </form>

            ';
                } else {

                    if ($_SESSION['role'] == 'organizer') {
                        $sql = "SELECT * FROM event WHERE id = :event_id";

                        $statement = $pdo->prepare($sql);
                        $statement->bindParam(":event_id", $id, PDO::PARAM_STR);
                        $statement->execute();

                        $data = $statement->fetch();
                        if ($data['organizer_id'] == $_SESSION['organizer_id']) {
                            echo '
                    <form action="delete-event.php" method="post" id="enroll">
            <input  type="hidden" name="event_id" value="' . $id . '"></input>
            <button class="buttonType1" type="submit" id="enrollButton" style="background-color:#c00000;" for="enroll">Cancel Event</button>
            </form>

            ';
                        } else {
                            echo '
                    <form action="enroll.php" method="post" id="enroll">
            <input  type="hidden" name="event_id" value="' . $id . '"></input>
            <button class="buttonType1" type="submit" id="enrollButton" for="enroll">Enroll</button>
            </form>

            ';
                        }
                    } else {
                        echo '
                    <form action="enroll.php" method="post" id="enroll">
            <input  type="hidden" name="event_id" value="' . $id . '"></input>
            <button class="buttonType1" type="submit" id="enrollButton" for="enroll">Enroll</button>
            </form>

            ';
                    }
                }
            }

            echo '
                </section>
                </section>
            

            ';

            function isExpired($data)
            {
                //returns true if the event has ended
                $date = explode(' ', $data['date']);
                $eventExactTime = strtotime($date[0] . ' ' . $data['startTime']);
                $currentDate = strtotime(date('Y-m-d H:i:s'));
                if ($currentDate > $eventExactTime) {
                    return true;
                }

                return false;
            }
            ?>
        </div>
    </section>

    <script>
        const menu = document.querySelector('.dropdownMenu');

        document.querySelector('.dropdown').addEventListener("click", function() {
            toggleDropdown();
        });

        function toggleDropdown() {
            if (menu.style.display === 'flex') {
                menu.style.display = 'none'
            } else {
                menu.style.display = 'flex'
            }
        }
    </script>
</body>

</html>