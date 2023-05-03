<?php
// Check if user is logged in
// Connect to database
require_once("config.php");
$db = get_pdo_connection();
if (isset($_SESSION['uID'])) {
    // Display logout and edit profile links
    $logout_link = "<a href='logout.php'>Logout</a>";
    $edit_profile_link = "<a href='edit-profile.php'>Edit Profile</a>";
} else {
    // Display register and login links
    $register_link = "<a href='register.php'>Register</a>";
    $login_link = "<a href='login.php'>Login</a>";
}
?>
<!DOCTYPE html>
<html>

<head>
    <title>Spark Social</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300&display=swap" rel="stylesheet">
    <link rel="apple-touch-icon" sizes="180x180" href="apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="favicon-16x16.png">
    <link rel="manifest" href="site.webmanifest">
</head>

<body>
    <header>
        <div class="container">
            <a href="sparksocial.php"><img src="images/sparksociallogo.png" alt="navbar-logo" class="logo"
                    style="width:75px; height:75px;"></a>

            <nav>
                <ul>
                    <li><a href="sparksocial.php">Public</a></li>
                    <li><a href="friends.php">Friends</a></li>
                    <li style="justify-content: center;"><a href="closefriends.php">Close Friends</a></li>
                    <li><a href="tos.html">TOS</a></li>
                    <li><a href="faq.html">FAQ</a></li>
                    <li><a href="about.html">About Us</a></li>
                </ul>
            </nav>
        </div>
    </header>


    <main>
        <div class="wrapper">
            <div class="dropdown">
                <img src="images/pfp.png" width="57" height="57" />
                <div class="dropdown-menu">
                    <?php
                    // Output the appropriate links based on user login status
                    if (isset($logout_link) && isset($edit_profile_link)) {
                        echo $logout_link;
                        echo $edit_profile_link;
                    } else {
                        echo $register_link;
                        echo $login_link;
                    }
                    ?>
                </div>
            </div>
        </div>




        <center class="text">
            <div class="line">
                <h1 class='lineUp'>Login.</h1>
            </div>


            <form action="login.php" method="POST">
                <div class="container">
                    <label for="email"><b>Email</b></label>
                    <input type="text" placeholder="Enter Email" name="email" required>

                    <label for="password"><b>Password</b></label>
                    <input type="password" placeholder="Enter Password" name="Password" required>

                    <?php
                    if (isset($error_message)) {
                        echo "<p class='error-message'>$error_message</p>";
                    }
                    ?>

                    <button type="submit">Login</button>
                </div>
            </form>
        </center>
        <?php
        // Check if form has been submitted
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Get email and password from form
            $email = $_POST['email'];
            $password = $_POST['Password'];

            // Check if connection was successful
            if (!$db) {
                die('Error connecting to database');
            }

            // Prepare SQL statement to retrieve email and password
            // Prepare SQL statement to retrieve email and password
            $stmt = $db->prepare("SELECT email, Password, uID FROM users WHERE email = ?");

            // Bind parameters to statement
            $stmt->bindParam(1, $email);

            // Execute statement
            $stmt->execute();

            // Fetch results
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            // Check if email and password are valid
            if ($result && password_verify($password, $result['Password'])) {
                // Redirect user to dashboard
                $_SESSION['uID'] = $result['uID'];
                header('Location: sparksocial.php');
                exit;
            } else {
                // Display error message
                echo ('whats going on');
                $error_message = 'Invalid email or password';
            }
        }
        ?>