<?php
session_start();

if (isset($_SESSION['role'])) {
    if ($_SESSION['role'] != 'organizer') {
        header('Location: index.php');
    }
} else {
    header('Location: index.php');
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles/font.css">
    <link rel="stylesheet" href="styles/elements.css">
    <link rel="stylesheet" href="styles/create-event.css">
    <link rel="icon" type="image/png" href="assets/images/icon.png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,100..1000;1,9..40,100..1000&display=swap" rel="stylesheet">
    <title>Eventure</title>
</head>

<body>
    <section class="parent">
        <img src="assets/images/amman.jpg" id="backgroundImage">
        <div class="content" id="content">
            <h2>Create Event</h2>
            <section style="display: flex; flex-direction: column; align-items: center;">
                <form class="formSection" action="create-event.php" method="post" id="createEventForm" enctype="multipart/form-data">
                    <section id="leftSide">
                        <section class="field">
                            <label class="label" for="title">title:</label>
                            <input class="textField1 input" id="title" type="text" name="title">
                        </section>
                        <section class="field">
                            <label class="label" for="description">description:</label>
                            <br>
                            <textarea class="textField2 input" id="description" type="text" name="description"></textarea>
                        </section>
                        <section class="field">
                            <label class="label" for="date">date:</label>
                            <input class="textField1 input" id="date" type="date" name="date" min="<?php echo date('Y-m-d'); ?>">
                        </section>
                        <section class="field">
                            <label class="label" for="startTime">start time:</label>
                            <input class="textField1 small input" id="startTime" type="time" name="startTime">
                            <br>
                            <label class="label" for="endTime">end time:</label>
                            <input class="textField1 small input" id="endTime" type="time" name="endTime">
                            <section class="field">
                                <label class="label" for="location">google maps link:</label>
                                <br>
                                <input class="textField2 input" id="location" type="text" name="location">
                            </section>
                        </section>
                    </section>
                    <section>
                        <section class="field">
                            <section>
                                <label class="label" for="city">city:</label>
                                <select class="select2 small" id="city" name="city">
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
                        </section>
                        <section class="field">
                            <section>
                                <label class="label" for="fee">fee:</label>
                                <input class="textField1 small input" id="fee" type="number" name="fee">
                            </section>
                            <section>
                                <label class="label" for="size">size:</label>
                                <input class="textField1 small input" id="size" type="number" name="size">
                            </section>
                        </section>
                        <section class="dropdowns">
                            <select class="select input" id="category" name="category">
                                <option value="0">Category</option>
                                <option value="1">Outdoor</option>
                                <option value="2">Community</option>
                                <option value="3">Sports & Competitions</option>
                                <option value="4">Workshops & Classes</option>
                            </select>
                            <select class="select input" id="subcategory" name="subcategory">
                                <option value="0">Subcategory</option>
                            </select>
                        </section>
                        <section class="field">
                            <label class="label" id="eventImageLabel" for="eventImage">image</label>
                            <br>
                            <label class="buttonType3" id="upload" for="eventImage">
                                upload image
                                <img src="assets/icons/upload.png" style="width: 25px; height: auto; margin-top: 10px;">
                            </label>
                            <br>
                            <input class="file" id="eventImage" type="file" name="eventImage">
                        </section>
                    </section>
                </form>
                <button type="submit" class="buttonType1" id="createEventButton" for="createEventForm" name="event">Create Event</button>
            </section>
        </div>
    </section>
    <script src="scripts/create-event.js">
    </script>
</body>

</html>

<?php
require 'connect.php';

$fileName = $_SESSION['username'] . date('Y-m-d_H-i-s');
if (isset($_FILES['eventImage'])) {
    if (move_uploaded_file($_FILES['eventImage']['tmp_name'], "uploaded/" . $fileName)) {
        echo 'console.log("uploaded successfully");';
    }
}

if (
    isset($_POST['title']) && isset($_POST['description']) && isset($_POST['date'])
    && isset($_POST['startTime']) && isset($_POST['endTime']) && isset($_POST['city']) && isset($_POST['fee'])
    && isset($_POST['location'])
) {
    $sql = "INSERT INTO
    `event`(`title`, `description`, `date`, `startTime`, `endTime`, `city`, `location`, `fee`, `size`, `category`, `subcategory`, `image`, `organizer_id`)
    VALUES (:title,:description,:date,:startTime,:endTime,:city,:location,:fee,:size,:category,:subcategory,:image,:organizer_id)";

    $statement = $pdo->prepare($sql);
    $title = $_POST['title'];
    $description = $_POST['description'];
    $date = $_POST['date'];
    $startTime = $_POST['startTime'];
    $endTime = $_POST['endTime'];
    $city = $_POST['city'];
    $fee = $_POST['fee'];
    if (isset($_POST['location'])) {
        $location = $_POST['location'];
    } else {
        $location = "";
    }
    $size = $_POST['size'];
    $category = $_POST['category'];
    $subcategory = $_POST['subcategory'];
    if (isset($_FILES['eventImage'])) {
        $image = "uploaded/" . $fileName;
    } else {
        $image = 'uploaded/dummy.png';
    }
    $organizer_id = $_SESSION['organizer_id'];
    $statement->bindParam(":title", $title, PDO::PARAM_STR);
    $statement->bindParam(":description", $description, PDO::PARAM_STR);
    $statement->bindParam(":date", $date, PDO::PARAM_STR);
    $statement->bindParam(":startTime", $startTime, PDO::PARAM_STR);
    $statement->bindParam(":endTime", $endTime, PDO::PARAM_STR);
    $statement->bindParam(":city", $city, PDO::PARAM_STR);
    $statement->bindParam(":fee", $fee, PDO::PARAM_STR);
    $statement->bindParam(":location", $location, PDO::PARAM_STR);
    $statement->bindParam(":size", $size, PDO::PARAM_INT);
    $statement->bindParam(":category", $category, PDO::PARAM_STR);
    $statement->bindParam(":subcategory", $subcategory, PDO::PARAM_STR);
    $statement->bindParam(":image", $image, PDO::PARAM_STR);
    $statement->bindParam(":organizer_id", $organizer_id, PDO::PARAM_STR);
    $statement->execute();

    echo '<script> 
    window.location.href="profile.php" 
    document.getElementById("createEventForm").reset();
    </script>';
    exit();
}
?>