<?php
require_once("config.php");
$db = get_pdo_connection();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Handle form submission
    $content = $_POST['cID'];
    $user_id = $_SESSION['uID'];

    // Insert new post into database
    $stmt = $db->prepare("INSERT INTO posts (user_id, content) VALUES (?, ?)");
    $stmt->execute([$uID, $Content]);
}

?>

<!DOCTYPE html>
<html>

<head>
    <title>Direct Messages</title>
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




        <center class="text">
            <div class="line">
                <h1 class='lineUp'>Direct Messages</h1>
            </div>
        </center>

        <center style="padding-bottom: 100px;">

            <?php
            // Check if user is logged in
            if (isset($_SESSION['uID'])) {
                $stmt = $db->prepare("SELECT u.username, u.profilepic, u.uID
                          FROM Canfriend c
                          INNER JOIN users u ON (c.uID1 = u.uID OR c.uID2 = u.uID)
                          WHERE (c.uID1 = :uid OR c.uID2 = :uid) AND c.isfriend = 1 AND c.isclosefriend = 0 AND u.uID <> :uid");
                $stmt->execute(array(':uid' => $_SESSION['uID']));
                $friends = $stmt->fetchAll(PDO::FETCH_ASSOC);

                if (count($friends) == 0) {
                    echo "You don't have any friends yet.";
                } else {
                    foreach ($friends as $friend) {
                        echo "<div style='display:flex; align-items:center; justify-content: center;'>";
                        echo "<p style='margin-right: 10px;'>" . $friend['username'] . "</p>";
                        echo "<a href='chatroom.php?friend_id=" . $friend['uID'] . "' target='_blank'><img style='padding: 20px; width: 125px; border-radius: 50%; height: 125px;' src='images/" . $friend['profilepic'] . "' alt='Profile Picture'></a>";
                        echo "</div>";
                    }



                }
            } else {
                echo "You must log in to view your friends.";
            }
            ?>








        </center>


    </main>
</body>

</html>