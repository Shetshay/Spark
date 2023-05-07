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
    <title>Messenger</title>
    <link rel="stylesheet" type="text/css" href="chatroom.css">
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



        <center>

            <?php
            // Get the friend_id from the URL parameter
            if (isset($_GET['friend_id'])) {
                $friend_id = $_GET['friend_id'];

                // Fetch the username of the friend from the database
                $stmt = $db->prepare("SELECT username FROM users WHERE uID = :friend_id");
                $stmt->execute(array(':friend_id' => $friend_id));
                $result = $stmt->fetch(PDO::FETCH_ASSOC);

                // Display the friend's username on the page
                $friend_username = $result['username'];
                echo "<center class='text'>";
                echo "<div class='line'>";
                echo "<h1 class='lineUp'>$friend_username</h1>";
                echo "</div>";
                echo "</center>";

            } else {
                echo "Error: No friend ID specified.";
            }
            ?>




        </center>

        <center>
            <div style="position: absolute; bottom: 75px; right: 50%; left: 40%;">
                <div style="justify-content: center;" class="Message">
                    <input title="Write Message" tabindex="i" pattern="\d+" placeholder="Message.." class="MsgInput"
                        type="text">
                    <svg xmlns="http://www.w3.org/2000/svg" version="1.0" width="30.000000pt" height="30.000000pt"
                        viewBox="0 0 30.000000 30.000000" preserveAspectRatio="xMidYMid meet" class="SendSVG">
                        <g transform="translate(0.000000,30.000000) scale(0.100000,-0.100000)" fill="#ffffff70"
                            stroke="none">
                            <path
                                d="M44 256 c-3 -8 -4 -29 -2 -48 3 -31 5 -33 56 -42 28 -5 52 -13 52 -16 0 -3 -24 -11 -52 -16 -52 -9 -53 -9 -56 -48 -2 -21 1 -43 6 -48 10 -10 232 97 232 112 0 7 -211 120 -224 120 -4 0 -9 -6 -12 -14z">
                            </path>
                        </g>
                    </svg><span class="l"></span>

                </div>
            </div>




        </center>

    </main>
</body>

</html>