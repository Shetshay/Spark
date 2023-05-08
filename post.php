<?php
require_once("config.php");
$db = get_pdo_connection();

?>

<!DOCTYPE html>
<html>

<head>
    <title>Spark Social</title>
    <link rel="stylesheet" type="text/css" href="createpost.css">
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
                <?php
                if (isset($_SESSION['uID'])) {
                    // get user's current profile picture
                    $stmt = $db->prepare("SELECT profilepic FROM users WHERE uID = ?");
                    $stmt->execute(array($_SESSION['uID']));
                    $result = $stmt->fetch(PDO::FETCH_ASSOC);
                    $picture = $result['profilepic'];
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
                        echo "<a href='inbox.php'>Inbox</a>";
                        echo "<a href='addfriends.php'>Add Friend</a>";
                        echo "<a href='directmessages.php'>Direct Messages</a>";
                        echo "<a href='post.php'>Create Post</a>";
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
        </div>

        <center class="text">
            <div class="line">
                <h1 class='lineUp'>Create a Post</h1>
            </div>
        </center>

        <center>
            <?php
            // Check if user is logged in
            if (isset($_SESSION['uID'])) {
                // Display post form
                echo '
        <form action="post.php" method="post" enctype="multipart/form-data">
            <label style="font-size: 16px;" for="post-level"></label>
            <br>
            <input class="input" type="radio" id="public-level" name="radio" value="10" checked>
            <label for="public-level">Public</label>
            <br>
            <input class="input" type="radio" id="friends-level" name="radio" value="20">
            <label for="friends-level">Friends</label>
            <br>
            <input class="input" type="radio" id="close-friends-level" name="radio" value="30">
            <label for="close-friends-level">Close Friends</label>
            <br><br>
            <label for="post-content"></label>
            <br>
            <textarea class="textareacomment" rows="4" cols="50" name="post_content"></textarea>
            <br><br>
            <label for="post-media">Media (optional)</label>
            <input type="file" name="post_media">
            <br><br>
            <input type="submit" name="post_submit" value="UPLOAD POST">
        </form>
    ';

                // Handle post submission
                if (isset($_POST['post_submit'])) {
                    $post_content = $_POST['post_content'];
                    $post_level = $_POST['radio'];
                    $post_media = $_FILES['post_media']['name'];

                    $user_id = $_SESSION['uID'];
                    $stmt = $db->prepare("INSERT INTO Content (cID, uID, text, Media, level) VALUES (NULL, :uID, :text, :Media, :level)");
                    $stmt->bindValue(':uID', $user_id, PDO::PARAM_INT);
                    $stmt->bindValue(':text', $post_content, PDO::PARAM_STR);
                    $stmt->bindValue(':Media', $post_media, PDO::PARAM_STR);
                    $stmt->bindValue(':level', $post_level, PDO::PARAM_INT);
                    $stmt->execute();
                    
                    // Save media file
                    if (!empty($post_media)) {
                        $target_dir = "media/";
                        $target_file = $target_dir . basename($_FILES["post_media"]["name"]);
                        move_uploaded_file($_FILES["post_media"]["tmp_name"], $target_file);
                    }

                    echo "Post uploaded successfully!";
                }
            } else {
                // Customer cannot post or view posts
                echo "You must login in order to post.";
            }
            ?>
        </center>
</body>

</html>