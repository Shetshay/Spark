<!DOCTYPE html>
<html>

<head>
    <title>FAQ</title>
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
                    <li><a href="tos.php">TOS</a></li>
                    <li><a href="faq.php">FAQ</a></li>
                    <li><a href="about.php">About Us</a></li>
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
                    // Check if user is logged in
                    session_start();
                    if (isset($_SESSION['uID'])) {
                        // Display logout and edit profile links
                        echo "<a href='logout.php'>Logout</a>";
                        echo "<a href='edit-profile.php'>Edit Profile</a>";
                    } else {
                        // Display register and login links
                        echo "<a href='register.php'>Register</a>";
                        echo "<a href='login.php'>Login</a>";
                    }
                    ?>
                </div>
            </div>
        </div>
        </div>



        <center class="text">
            <div class="line">
                <h1 class='lineUp'>FAQ.</h1>
            </div>
            <p style="width:50%; font-size: 24px;">
            <ul>
                Q: What is Spark Social?
            </ul>
            <li>
                A: Spark Social is a social media platform that enables people to connect with others, share their
                thoughts, ideas, and interests.
            </li>

            <ul>
                Q: Is Spark Social free to use?
            </ul>
            A: Yes, Spark Social is free to use. However, we may introduce premium features in the future that will
            require a fee.
            <ul>
                Q: Is my personal information safe on Spark Social?
            </ul>
            A: Yes, we take the security of our users' personal information very seriously. We use industry-standard
            security measures to protect your data, and we do not share your information with third parties without your
            consent.
            <ul>
                Q: Can I create a business profile on Spark Social?
            </ul>
            A: Yes, you can create a business profile on Spark Social. However, we have specific guidelines for business
            profiles, and we reserve the right to remove any profiles that violate our policies.
            <ul>
                Q: What kind of content is allowed on Spark Social?
            </ul>
            A: We allow all kinds of content on Spark Social, as long as it does not violate our community guidelines.
            We do not tolerate hate speech, harassment, or any other form of harmful or offensive content.
            </p>
        </center>

        <!--
         <?php
         require_once("config.php");
         session_start();
         if (isset($_SESSION['username'])) {
             echo "<p>Welcome, " . $_SESSION['username'] . "!</p>";
             echo "<p><a href='logout.php'>Logout</a></p>";
         } else {
             echo "<p><a href='login.php'>Login</a> or <a href='register.php'>Register</a></p>";
         }
         ?>

      -->
    </main>
</body>

</html>