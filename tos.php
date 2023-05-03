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
                <h1 class='lineUp'>Terms of Service.</h1>
            </div>
            <p style="width:50%; font-size: 24px;">
                Welcome to Spark Social!

                These terms and conditions govern your use of the Spark Social website and related services. By using
                our platform, you agree to these terms and conditions. If you do not agree to these terms and
                conditions, you should not use Spark Social.

                User Conduct: You agree to use Spark Social for lawful purposes only and not to post any content that is
                abusive, threatening, defamatory, or obscene.

                Privacy: We take the privacy of our users seriously and will not share your personal information with
                third parties without your consent.

                Intellectual Property: You retain all rights to any content you post on Spark Social. However, by
                posting content on our platform, you grant us a non-exclusive, transferable, royalty-free license to
                use, modify, and distribute your content.

                Termination: We reserve the right to terminate your account at any time, for any reason, without notice.

                Disclaimer: Spark Social is provided on an "as is" and "as available" basis. We make no representations
                or warranties of any kind, express or implied, as to the operation of our platform or the information,
                content, materials, or products included on our platform.

                Indemnification: You agree to indemnify and hold harmless Spark Social, its affiliates, and their
                respective officers, directors, employees, and agents, from any claim or demand, including reasonable
                attorneys' fees, made by any third party due to or arising out of your use of our platform, your
                violation of these terms and conditions, or your violation of any rights of another.

                Governing Law: These terms and conditions shall be governed by and construed in accordance with the laws
                of [INSERT YOUR JURISDICTION HERE], without giving effect to any principles of conflicts of law.

                Changes to the Terms: We reserve the right to change these terms and conditions at any time, without
                notice. It is your responsibility to review these terms and conditions regularly.



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