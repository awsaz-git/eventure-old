<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles/signup.css">
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
            <h2>Create Account</h2>
            <section id="step2">
                <form class="formSection" action="create-account.php" method="post" id="signupForm">
                    <section id="step2-1">
                        <section class="field">
                            <label class="label" id="firstNameLabel" for="firstName">first name</label>
                            <br>
                            <input class="textField2" id="firstName" type="text" name="firstName">
                        </section>
                        <section class="field">
                            <label class="label" id="lastNameLabel" for="lastName">last name</label>
                            <br>
                            <input class="textField2" id="lastName" type="text" name="lastName">
                        </section>
                        <section class="field">
                            <label class="label" id="phoneNumberLabel" for="phoneNumber">phone number</label>
                            <br>
                            <input class="textField2" id="phoneNumber" type="number" name="phoneNumber" value="07">
                        </section>
                        <section class="field">
                            <label class="label" id="dateOfBirthLabel" for="dateOfBirth">date of birth</label>
                            <br>
                            <input class="textField2" id="dateOfBirth" type="date" name="dateOfBirth" max="<?php echo date('Y-m-d'); ?>">
                        </section>
                        <section class="field" id="radioButtons">
                            <label class="label" id="maleLabel" for="male">male</label>
                            <input id="male" type="radio" name="gender" value="male">
                            <label class="label" id="femaleLabel" for="female">female</label>
                            <input id="female" type="radio" name="gender" value="female">
                        </section>
                        <img class="next" src="assets/icons/arrow.png"
                            onclick="navigate('step2-1','step2-2')">
                    </section>
                    <section class="step" id="step2-2">
                        <section class="field">
                            <label class="label" id="companyNameLabel" for="companyName">company name</label>
                            <br>
                            <input class="textField2" id="companyName" type="text" name="companyName">
                        </section>
                        <section class="field">
                            <label class="label" id="addressLabel" for="address">address</label>
                            <br>
                            <input class="textField2" id="address" type="text" name="address">
                        </section>
                        <section class="field">
                            <label class="label" id="verificationFileLabel" for="verificationFile">verification file</label>
                            <br>
                            <label class="buttonType3" id="upload" for="verificationFile">
                                upload file
                                <img src="assets/icons/upload.png" style="width: 25px; height: auto; margin-top: 10px;">
                            </label>
                            <br>
                            <input class="file" id="verificationFile" type="file" name="verificationFile">
                        </section>
                        <img class="next" src="assets/icons/arrow.png"
                            onclick="navigate('step2-2','step2-3')">
                    </section>
                    <section class="step" id="step2-3">
                        <section class="field">
                            <label class="label" id="emailLabel" for="email">email</label>
                            <br>
                            <input class="textField2" id="email" type="text" name="email">
                        </section>
                        <section class="field">
                            <label class="label" id="usernameLabel" for="username">username</label>
                            <br>
                            <input class="textField2" id="username" type="text" name="username">
                        </section>
                        <section class="field" id="passwordField">
                            <label class="label" id="passwordLabel" for="password">password</label>
                            <br>
                            <input class="textField2 password" id="password" type="password" name="password" oncopy="return false;"
                                oncut="return false;" onpaste="return false;">
                        </section>
                        <section class="passwordRules">
                            <img src="assets/icons/wrong.png" class="img capitalization" id="capitalization">
                            <div>at least one capital & small letter</div>
                        </section>
                        <section class="passwordRules">
                            <img src="assets/icons/wrong.png" class="img specialChars" id="specialChars">
                            <div>at least one special character</div>
                        </section>
                        <section class="passwordRules">
                            <img src="assets/icons/wrong.png" class="img length" id="length">
                            <div>at least 8 characters</div>
                        </section>
                        <button type="button" class="buttonType1" id="signupButton" for="signupForm" onclick="account_type=1;">Signup</button>
                    </section>
                </form>
            </section>
    </section>

    </div>
    </section>

    <script>
        var error = <?php
                    if (isset($_GET['error'])) {
                        echo $_GET['error'];
                    } else {
                        echo null;
                    }
                    ?>;
    </script>
    <script src="scripts/signup.js">
    </script>
</body>

</html>