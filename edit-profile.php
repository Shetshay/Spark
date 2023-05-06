<?php
require_once("config.php");
$db = get_pdo_connection();
//error_reporting(E_ERROR | E_WARNING | E_PARSE);
//DELETE THE LINE ABOVE TO REVEAL ERRORS WHEN NEEDED

// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

//remove code aboce to stop showing errors

if (isset($_POST['bio'])) {
    $bio = $_POST['bio'];
    $uID = $_SESSION['uID'];

    $stmt = $db->prepare("UPDATE users SET bio = :bio WHERE uID = :uID");
    $stmt->bindParam(":bio", $bio);
    $stmt->bindParam(":uID", $uID);
    $stmt->execute();

}
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
                    <li><a href="tos.php">TOS</a></li>
                    <li><a href="faq.php">FAQ</a></li>
                    <li><a href="about.php">About Us</a></li>
                </ul>
            </nav>
        </div>
    </header>


    <main style="padding-bottom: 400px;">
        <div class="wrapper">
            <div class="dropdown">

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
                            $file_ext_parts = explode('.', $file_name);
                            $file_ext = strtolower(end($file_ext_parts));

                            $extensions = array("jpeg", "jpg", "png", "gif");

                            if (in_array($file_ext, $extensions) === false) {
                                $errors[] = "Extension not allowed, please choose a JPEG, GIF, or PNG file.";
                            }
                            if ($file_ext == "gif" && $file_size > 2097152) {
                                $errors[] = 'File size must be less than 2 MB for GIF files';
                            }
                            if ($file_ext != "gif" && $file_size > 12582912) {
                                $errors[] = 'File size must be less than 12 MB for JPEG, JPG, or PNG files';
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


                    echo '<div class="profile-pic1"><img src="images/' . $picture . '" width="57" height="57" /></div>';
                } else {
                    echo '<div class="profile-pic1"><img src="images/pfp.png" width="57" height="57" /></div>';
                }
                ?>


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
                <h1 class='lineUp'>Edit profile</h1>
            </div>
        </center>
        <?php


        if (isset($_SESSION['uID'])) {
            // get user's current profile picture
            $stmt = $db->prepare("SELECT profilepic FROM users WHERE uID = ?");
            $stmt->execute(array($_SESSION['uID']));
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            $picture = $result['profilepic'];

            $errors = array(); // create an empty errors array
        
            if (isset($_POST['submit'])) {
                // handle profilepic upload
                if (isset($_FILES['profilepic'])) {
                    $file_name = $_FILES['profilepic']['name'];
                    $file_tmp = $_FILES['profilepic']['tmp_name'];
                    $file_type = $_FILES['profilepic']['type'];
                    $file_size = $_FILES['profilepic']['size'];
                    //under this is an error, grab file_size or remove end or figure something out.
                    $file_ext_parts = explode('.', $file_name);
                    $file_ext = strtolower(end($file_ext_parts));

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

            echo "<center <div class='profile-pic'>
                  <img src='images/$picture' alt='Profile Picture' width='200' height='200' onmouseover='this.style.opacity=0.7;' onmouseout='this.style.opacity=1;'>
                  <div class='edit-profile-picture'>
                      <form method='post' enctype='multipart/form-data'>
                          <input type='file' name='profilepic' accept='image/*'>
                          <input type='submit' name='submit' value='Upload'>
                      </form>
                  </div>
              </div> </center></div>";


        } else {
            // Customer cannot edit profile
            echo "You must login in order to edit your profile.";
        }

        if (isset($_SESSION['uID'])) {
            $stmt = $db->prepare("SELECT bio FROM users WHERE uID = ?");
            $stmt->execute([$_SESSION['uID']]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $bio = $row['bio'];

            if ($bio === null) {
                echo "<center>No bio, yet!</center>";
            } else {
                echo "<center style='padding-top: 45px;'>Current Bio: " . $bio . "</center>";
            }

            echo "<center><form method='post'>";
            echo "<textarea class = 'textareacomment'name='newBio' rows='5' cols='50'>" . $bio . "</textarea>";
            echo "<br><input type='submit' name='updateBio' value='Update Bio'>";
            echo "</form></center>";

            if (isset($_POST['updateBio'])) {
                $newBio = $_POST['newBio'];
                $stmt = $db->prepare("UPDATE users SET bio = ? WHERE uID = ?");
                $stmt->execute([$newBio, $_SESSION['uID']]);
                header("Location: edit-profile.php");
            }
        }
        if (isset($_SESSION['uID'])) {
            $stmt = $db->prepare("SELECT email FROM users WHERE uID = ?");
            $stmt->execute([$_SESSION['uID']]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $email = $row['email'];

            if ($email === null) {
                echo "<center>No email, yet!</center>";
            } else {
                echo "<center style='padding-top: 45px;'>Current Email: " . $email . "</center>";
                echo "<center><form method='post'>";
                echo "<input type='text' name='newEmail' placeholder='New Email'>";
                echo "<br><input type='submit' name='updateEmail' value='Update Email'>";
                echo "</form></center>";

                if (isset($_POST['updateEmail'])) {
                    $newEmail = $_POST['newEmail'];

                    // Validate email format
                    if (!filter_var($newEmail, FILTER_VALIDATE_EMAIL)) {
                        echo "<center>Invalid email address.</center>";
                    } else {
                        $stmt = $db->prepare("UPDATE users SET email = ? WHERE uID = ?");
                        $stmt->execute([$newEmail, $_SESSION['uID']]);
                        echo "<center>Email updated successfully!</center>";
                        $email = $newEmail;
                        header("Location: edit-profile.php");
                    }
                }
            }
        }
        if (isset($_SESSION['uID'])) {
            $stmt = $db->prepare("SELECT username FROM users WHERE uID = ?");
            $stmt->execute([$_SESSION['uID']]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $username = $row['username'];

            if ($username === null) {
                echo "<center>No username, yet!</center>";
            } else {
                echo "<center style='padding-top: 45px;'>Current Username: " . $username . "</center>";
                echo "<center><form method='post'>";
                echo "<input type='text' name='newUsername' placeholder='New Username'>";
                echo "<br><input type='submit' name='updateUsername' value='Update Username'>";
                echo "</form></center>";

                if (isset($_POST['updateUsername'])) {
                    $newUsername = $_POST['newUsername'];
                    // Add validation here to check if $newUsername is not empty, is unique, etc.
        
                    $stmt = $db->prepare("UPDATE users SET username = ? WHERE uID = ?");
                    $stmt->execute([$newUsername, $_SESSION['uID']]);
                    $username = $newUsername; // Update the username variable with the new value
                    echo "<center>Username updated successfully!</center>";
                    header("Location: edit-profile.php");
                }
            }
        }
        if (isset($_SESSION['uID'])) {
            $stmt = $db->prepare("SELECT password FROM users WHERE uID = ?");
            $stmt->execute([$_SESSION['uID']]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $password = $row['password'];

            if ($password === null) {
                echo "<center>No password, yet!</center>";
            } else {
                echo "<center style='padding-top: 45px;'>Update Password:</center>";
                echo "<center><form method='post'>";
                echo "<input type='password' name='currentPassword' placeholder='Current Password'>";
                echo "<br><input type='password' name='newPassword' placeholder='New Password'>";
                echo "<br><input type='password' name='confirmNewPassword' placeholder='Confirm New Password'>";
                echo "<br><input type='submit' name='updatePassword' value='Update Password'>";
                echo "</form></center>";

                if (isset($_POST['updatePassword'])) {
                    $currentPassword = $_POST['currentPassword'];
                    $newPassword = $_POST['newPassword'];
                    $confirmNewPassword = $_POST['confirmNewPassword'];

                    // Check if the current password is correct
                    if (password_verify($currentPassword, $password)) {
                        // Check if the new password meets the requirements
                        if (strlen($newPassword) >= 8 && preg_match('/[A-Za-z]/', $newPassword) && preg_match('/\d/', $newPassword)) {
                            // Check if the new password and confirm new password match
                            if ($newPassword === $confirmNewPassword) {
                                // Update the user's password in the database
                                $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
                                $stmt = $db->prepare("UPDATE users SET password = ? WHERE uID = ?");
                                $stmt->execute([$hashedPassword, $_SESSION['uID']]);
                                echo "<center>Password updated successfully!</center>";
                                $password = $hashedPassword; // Update the local variable with the new hashed password
                            } else {
                                echo "<center style='color: red;'>New password and confirm new password do not match.</center>";
                            }
                        } else {
                            echo "<center style='color: red;'>New password must be at least 8 characters long and contain at least one number.</center>";
                        }
                    } else {
                        echo "<center style='color: red;'>Current password is incorrect.</center>";
                    }
                }
            }
        }





        ?>


    </main>
</body>

</html>