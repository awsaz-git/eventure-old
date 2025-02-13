<?php
session_start();

global $isOrganizer;

if (isset($_SESSION['username'])) {

    if ($_SESSION['role'] == 'organizer') {
        $isOrganizer = true;
    } else {
        $isOrganizer = false;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta property="og:image" content="/assets/images/icon2.png">
    <meta name="title" content="Eventure">
    <meta name="description" content="Eventure is your one-stop destination for discovering and hosting events across Jordan. 
    Explore cultural festivals, workshops, sports tournaments, and more. Connect and share your passion through events.">
    <meta name="keywords" content="amman, irbid, jordan, events, events in Jordan, event hosting, cultural events, workshops, sports tournaments, community activities, 
    outdoor events, hiking trips, camping trips, city tours, marathons, volunteering, football, basketball, volleyball, arts, digital skills, 
    photography, videography, personal development, languages">
    <meta name="author" content="Eventure">
    <meta name="robots" content="index, follow">

    <link rel="stylesheet" href="styles/style.css">
    <link rel="stylesheet" href="styles/font.css">
    <link rel="stylesheet" href="styles/elements.css">
    <link rel="icon" type="image/png" href="assets/images/icon.png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,100..1000;1,9..40,100..1000&display=swap" rel="stylesheet">
    <title>Eventure</title>
</head>

<body>
    <header class="navbar">
        <a href="index.php">
            <img src="assets/images/logo.png" id="logo">
        </a>
        <section id="actionButtons">
            <a id="aboutUs" href="about-us.php">
                About Us
            </a>
            <?php
            require 'connect.php';

            if ($isOrganizer) {
                echo '
                <a id="aboutUs" href="create-event.php">
                Create Event!
            </a>
                ';
            }
            if (isset($_SESSION['username'])) {
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

                echo '<a href="profile.php">
                <img src="' . $image . '" class="profileImage">
            </a>
            ';
            } else {
                echo '<button id="loginButton" class="buttonType1" onclick="window.location.href = \'login.php\'">Login</button>';
            }
            ?>


        </section>
    </header>
    <form class="searchBar" action="" method="get">
        <section>
            <label class="label" id="searchEventLabel" for="searchEvent"> Search Event </label>
            <br>
            <input class="textField1" id="searchEvent" type="text" name="searchEvent">
        </section>

        <section>
            <label class="label" id="placeLabel" for="place"> Place </label>
            <br>
            <select class="select2" id="place">
                <option value="0"></option>
                <option value="amman">Amman</option>
                <option value="irbid">Irbid</option>
                <option value="zarqa">Zarqa</option>
                <option value="salt">Al-Salt</option>
                <option value="mafraq">Al-Mafraq</option>
                <option value="karak">Al-Karak</option>
                <option value="madaba">Madaba</option>
                <option value="jaresh">Jaresh</option>
                <option value="ajloun">Ajloun</option>
                <option value="aqaba">Aqaba</option>
                <option value="maan">Ma'an</option>
                <option value="tafeela">Al-Tafeela</option>
            </select>
        </section>

        <section>
            <label class="label" id="dateLabel" for="date"> Date </label>
            <br>
            <input class="textField1" id="date" type="date" name="date" min="<?php echo date('Y-m-d'); ?>">
        </section>
    </form>
    <div class=" content">
        <section id="filters">
            <h2>
                Upcoming
            </h2>
            <section class="dropdowns">
                <select class="select" id="category">
                    <option value="0">Category</option>
                    <option value="1">Outdoor</option>
                    <option value="2">Community</option>
                    <option value="3">Sports & Competitions</option>
                    <option value="4">Workshops & Classes</option>
                </select>
                <select class="select" id="subcategory">
                    <option value="0">Subcategory</option>
                    <!-- <option value="1.1">Hiking Trips</option>
                    <option value="1.2">Camping Trips</option>
                    <option value="1.3">City Tours</option>
                    <option value="1.4">Marathons</option>
                    <option value="2.1">Volunteering</option>
                    <option value="3.1">Football</option>
                    <option value="3.2">Basketball</option>
                    <option value="3.3">Volleyball</option>
                    <option value="3.4">Competitions</option>
                    <option value="4.1">Arts</option>
                    <option value="4.2">Digital Skills</option>
                    <option value="4.3">Photography & Videography</option>
                    <option value="4.4">Personal Development</option>
                    <option value="4.5">Languages</option> -->
                </select>
            </section>
        </section>
        <section class="cards">
            <!-- events go here, they are generated using php code below -->
            <!-- this one is a template but is not visable on the website -->
            <div class="eventCard" data-id="1" onclick="window.location.href='event.php?id=1'" id="templateCard">
                <img src="assets/images/football.jpg" class="eventImage">
                <section class="eventInfo">
                    <section class="date">
                        <div class="month">DEC</div>
                        <div class="day">25</div>
                    </section>
                    <section class="description">
                        <div class="title">Football Game - 6:00PM</div>
                        <div class="location">Mayar Schools</div>
                    </section>
                </section>
            </div>
            <!-- -->
            <?php
            require 'connect.php';

            if (isset($_GET['category'])) {
                $category = $_GET['category'];
            } else {
                $category = 0;
            }
            if (isset($_GET['subcategory'])) {
                $subcategory = $_GET['subcategory'];
            } else {
                $subcategory = 0;
            }

            switch ($category) {
                case 0:
                    $sql = "SELECT * FROM event ORDER BY date ASC";
                    break;

                case 1:
                    switch ($subcategory) {
                        case 0:
                            $sql = "SELECT * FROM event WHERE category = 1 ORDER BY date ASC";
                            break;
                        case 1.1:
                            $sql = "SELECT * FROM event WHERE category = 1 AND subcategory = 1.1 ORDER BY date ASC";
                            break;
                        case 1.2:
                            $sql = "SELECT * FROM event WHERE category = 1 AND subcategory = 1.2 ORDER BY date ASC";
                            break;
                        case 1.3:
                            $sql = "SELECT * FROM event WHERE category = 1 AND subcategory = 1.3 ORDER BY date ASC";
                            break;
                        case 1.4:
                            $sql = "SELECT * FROM event WHERE category = 1 AND subcategory = 1.4 ORDER BY date ASC";
                            break;
                    }
                    break;

                case 2:
                    switch ($subcategory) {
                        case 0:
                            $sql = "SELECT * FROM event WHERE category = 2 ORDER BY date ASC";
                            break;
                        case 2.1:
                            $sql = "SELECT * FROM event WHERE category = 2 AND subcategory = 2.1 ORDER BY date ASC";
                            break;
                    }
                    break;

                case 3:
                    switch ($subcategory) {
                        case 0:
                            $sql = "SELECT * FROM event WHERE category = 3 ORDER BY date ASC";
                            break;
                        case 3.1:
                            $sql = "SELECT * FROM event WHERE category = 3 AND subcategory = 3.1 ORDER BY date ASC";
                            break;
                        case 3.2:
                            $sql = "SELECT * FROM event WHERE category = 3 AND subcategory = 3.2 ORDER BY date ASC";
                            break;
                        case 3.3:
                            $sql = "SELECT * FROM event WHERE category = 3 AND subcategory = 3.3 ORDER BY date ASC";
                            break;
                        case 3.4:
                            $sql = "SELECT * FROM event WHERE category = 3 AND subcategory = 3.4 ORDER BY date ASC";
                            break;
                    }
                    break;

                case 4:
                    switch ($subcategory) {
                        case 0:
                            $sql = "SELECT * FROM event WHERE category = 4 ORDER BY date ASC";
                            break;
                        case 4.1:
                            $sql = "SELECT * FROM event WHERE category = 4 AND subcategory = 4.1 ORDER BY date ASC";
                            break;
                        case 4.2:
                            $sql = "SELECT * FROM event WHERE category = 4 AND subcategory = 4.2 ORDER BY date ASC";
                            break;
                        case 4.3:
                            $sql = "SELECT * FROM event WHERE category = 4 AND subcategory = 4.3 ORDER BY date ASC";
                            break;
                        case 4.4:
                            $sql = "SELECT * FROM event WHERE category = 4 AND subcategory = 4.4 ORDER BY date ASC";
                            break;
                        case 4.5:
                            $sql = "SELECT * FROM event WHERE category = 4 AND subcategory = 4.5 ORDER BY date ASC";
                            break;
                    }
                    break;
            }

            $statement = $pdo->prepare($sql);
            $statement->execute();

            $data = $statement->fetchAll();

            foreach ($data as $event) {

                if (!isExpired($event)) {
                    $href = "'event.php?id=" . $event['id'] . "'";

                    $date = explode('-', $event['date']);
                    $day = $date[2];
                    $month = strtoupper(date("M", mktime(0, 0, 0, $date[1])));
                    $startTime = date("g:iA", strtotime($event['startTime']));
                    $endTime = date("g:iA", strtotime($event['endTime']));
                    $category = translateCategory($event['category']);
                    if ($event['image'] == null) {
                        $image = "assets/images/dummy.png";
                    } else {
                        $image = $event['image'];
                    }

                    echo '<div class="eventCard" data-id="' . $event['id'] . '" onclick="window.location.href=' . $href . '">
                <img src="' . $image . '" alt="image not available" class="eventImage">
        <section class="eventInfo">
            <section class="date">
                <div class="month">' . $month . '</div>
                <div class="day">' . $day . '</div>
            </section>
            <section class="description">
                <div class="title">' . $event['title'] . ' - ' . $startTime . '</div>
                <div class="location">' . $category . '</div>
            </section>
        </section>
        </div>';
                }
            }

            function translateCategory($categoryNumber)
            {
                $categories = [
                    "1" => "Outdoor",
                    "1.1" => "Hiking Trips",
                    "1.2" => "Camping Trips",
                    "1.3" => "City Tours",
                    "1.4" => "Marathons",
                    "2" => "Community",
                    "2.1" => "Volunteering",
                    "3" => "Sports & Competitions",
                    "3.1" => "Football",
                    "3.2" => "Basketball",
                    "3.3" => "Volleyball",
                    "3.4" => "Competitions",
                    "4" => "Workshops & Classes",
                    "4.1" => "Arts",
                    "4.2" => "Digital Skills",
                    "4.3" => "Photography & Videography",
                    "4.4" => "Personal Development",
                    "4.5" => "Languages"
                ];

                $category = $categories[$categoryNumber];
                return $category;
            }

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
        </section>
    </div>

    <footer class="footer">
        <img src="assets/images/logo2.png" id="logo2">
        <div class="about">
            Eventure is your go-to platform for finding and hosting events across Jordan. Discover activities, meet new people, and create memorable experiences.
        </div>
        <div style="width: 100%; text-align: center; color: white;">
            Copyright © <?php echo date('Y'); ?> Eventure Jo, LLC. All Rights Reserved.
        </div>
    </footer>

    <script src="scripts/filter.js"></script>
</body>

</html>