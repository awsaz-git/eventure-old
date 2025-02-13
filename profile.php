<?php
session_start();
require 'connect.php';

if (!isset($_SESSION['username'])) {
    header('location: login.php');
}

if (isset($_FILES['profileImage'])) {
    $imagePath = "assets/profile_images/" . $_SESSION['username'] . date('Y-m-d_H-i-s');
    if (move_uploaded_file($_FILES['profileImage']['tmp_name'], $imagePath)) {
        $sql = "UPDATE account_info
SET profile_image = :profile_image
WHERE username = :username;";

        $statement = $pdo->prepare($sql);
        $statement->bindParam(":username", $_SESSION['username'], PDO::PARAM_STR);
        $statement->bindParam(":profile_image", $imagePath, PDO::PARAM_STR);
        $statement->execute();
    }

    header("Location: profile.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles/profile.css">
    <link rel="stylesheet" href="styles/font.css">
    <link rel="stylesheet" href="styles/elements.css">
    <link rel="icon" type="image/png" href="/assets/images/icon.png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,100..1000;1,9..40,100..1000&display=swap" rel="stylesheet">
    <title>Eventure</title>
</head>

<body>
    <section class="parent">
        <img src="assets/images/amman2.jpg" id="backgroundImage">
        <div class="content" id="content">
            <section class="topSection">
                <section>
                    <?php
                    require 'connect.php';

                    $sql = "SELECT 
        profile_image
    FROM 
        account_info
    WHERE 
        account_info.username = :username
    ";

                    $statement = $pdo->prepare($sql);
                    $statement->bindParam(":username", $_SESSION['username'], PDO::PARAM_STR);
                    $statement->execute();

                    $data = $statement->fetch();

                    if (isset($data['profile_image'])) {
                        $image = $data['profile_image'];
                    } else {
                        $image = "/assets/profile_images/generic.png";
                    }

                    echo '
                <form id="form" class="imageSection" action="profile.php" method="post" enctype="multipart/form-data">
                    <label for="profileImage">
                <img src="' . $image . '" class="profileImage">
            </label>
            <div class="name">' . $_SESSION['first_name'] . ' ' . $_SESSION['last_name'] . '</div>
            <input class="file" id="profileImage" type="file" name="profileImage" accept="image/*" onchange="document.getElementById(\'form\').submit();">
            ';
                    ?>
                </section>
                <a href="index.php">
                    <img src="/assets/icons/home.png" class="logout">
                </a>
            </section>

            <section class="upcommingEventsSection">


                <h3><?php
                    if ($_SESSION['role'] != 'organizer') {
                        echo 'Upcoming Events';
                    } else {
                        echo 'My Events';
                    }
                    ?></h3>
                <section class="section">
                    <?php
                    require 'connect.php';

                    if ($_SESSION['role'] != 'organizer') {
                        $sql = '
                    SELECT 
        enrolled_event.event_id,
        event.id,
        event.title,
        event.description,
        event.date,
        event.city,
        event.fee,
        event.image
    FROM 
        enrolled_event
    JOIN 
        event
    ON 
        enrolled_event.event_id = event.id
    WHERE 
        enrolled_event.enrolled_username = :username
    ORDER BY date ASC; 
                ';

                        $statement = $pdo->prepare($sql);
                        $statement->bindParam(":username", $_SESSION['username'], PDO::PARAM_STR);
                    } else {
                        $sql = '
                    SELECT 
        *
    FROM 
        event
    WHERE 
        organizer_id = :organizer_id
    ORDER BY date ASC; 
                ';

                        $statement = $pdo->prepare($sql);
                        $statement->bindParam(":organizer_id", $_SESSION['organizer_id'], PDO::PARAM_STR);
                    }
                    $statement->execute();

                    $data = $statement->fetchAll();

                    foreach ($data as $event) {
                        $href = "'event.php?id=" . $event['id'] . "'";

                        $date = explode('-', $event['date']);
                        $day = $date[2];
                        $month = strtoupper(date("M", mktime(0, 0, 0, $date[1])));
                        $city = $event['city'];
                        if ($event['image'] == null) {
                            $image = "assets/images/dummy.png";
                        } else {
                            $image = $event['image'];
                        }
                        echo '
                    <div class="eventCard">
                    <img class="image" src="' . $image . '" alt="image not available" class="eventImage" onclick="window.location.href=' . $href . '">
                    <section class="eventInfo">
                        <section class="description">
                            <div class="title">' . $event['title'] . '</div>
                            <div class="location">' . ucfirst($city) . '</div>
                            <div class="title">' . $event['fee'] . ' JOD' . '</div>
                        </section>
                        <section class="date">
                            <div class="month">' . $month . ' ' . $day . '</div>
                        </section>
                    </section>
                </div>
                    ';
                    }


                    ?>
                </section>


            </section>
            <a id="logoutButton" class="buttonType1" href="logout.php">Logout</a>
        </div>

    </section>

    <script>
    </script>
</body>

</html>

<?php
require 'connect.php';


?>