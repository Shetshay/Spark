<?php
require_once("config.php");
$db = get_pdo_connection();

?>

<!DOCTYPE html>
<html>

<head>
    <title>Edit Profile</title>
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
                <!-- we will need to edit this to allow the pfp to change for users logged in and users NOT logged in

-->
                <img src="images/pfp.png" width="57" height="57" />
                <div class="dropdown-menu">
                    <?php
                    // Check if user is logged in
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
                <h1 class='lineUp'>Edit profile.</h1>
            </div>
        </center>

        <?php


        if (isset($_SESSION['uID'])) {
            // get user's current profile picture
            $stmt = $db->prepare("SELECT profilepic FROM users WHERE uID = ?");
            $stmt->execute(array($_SESSION['uID']));
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            $picture = $result['profilepic'];

            if (isset($_POST['submit'])) {
                // handle profilepic upload
                if (isset($_FILES['profilepic'])) {
                    $file_name = $_FILES['profilepic']['name'];
                    $file_tmp = $_FILES['profilepic']['tmp_name'];
                    $file_type = $_FILES['profilepic']['type'];
                    $file_size = $_FILES['profilepic']['size'];
                    //under this is an error, grab file_size or remove end or figure something out.
                    $file_ext = strtolower(end(explode('.', $file_name)));

                    $extensions = array("jpeg", "jpg", "png", "gif");

                    if (in_array($file_ext, $extensions) === false) {
                        $errors[] = "Extension not allowed, please choose a JPEG, GIF, or PNG file.";
                    }

                    if ($file_size > 8388608) {
                        $errors[] = 'File size must be less than 8 MB';
                    }

                    if (empty($errors) == true) {
                        move_uploaded_file($file_tmp, "images/" . $file_name);
                        $picture = $file_name;
                        // update user's profile picture in the database
                        $stmt = $db->prepare("UPDATE users SET profilepic = ? WHERE uID = ?");
                        $stmt->execute(array($picture, $_SESSION['uID']));
                    }
                }
            }

            //Display logout and edit profile links
            //echo $picture;
            echo "<center <div class='profile-picture'>
                  <img src='images/$picture' alt='Profile Picture' width='200' height='200' onmouseover='this.style.opacity=0.7;' onmouseout='this.style.opacity=1;'>
                  <div class='edit-profile-picture'>
                      <form method='post' enctype='multipart/form-data'>
                          <input type='file' name='profilepic' accept='image/*'>
                          <input type='submit' name='submit' value='Save'>
                      </form>
                  </div>
              </div> </center>";



        } else {
            // Customer cannot edit profile
            echo "You must login in order to edit your profile.";
            usleep(30000);
        }
        ?>


    </main>
</body>

</html>