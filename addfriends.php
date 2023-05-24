<?php
require_once("config.php");
?>

<!DOCTYPE html>
<html>

<head>
    <title>Spark Social</title>
    <link rel="stylesheet" type="text/css" href="addfriends.css">
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
                    
                    $db = get_pdo_connection();
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
                <h1 class='lineUp'>Add Friends</h1>
            </div>
        </center>

        <center>
            <form class="form" method="POST" >
                <button>
                    <svg width="17" height="16" fill="none" xmlns="http://www.w3.org/2000/svg" role="img"
                        aria-labelledby="search">
                        <path d="M7.667 12.667A5.333 5.333 0 107.667 2a5.333 5.333 0 000 10.667zM14.334 14l-2.9-2.9"
                            stroke="currentColor" stroke-width="1.333" stroke-linecap="round" stroke-linejoin="round">
                        </path>
                    </svg>
                </button>
                <input class="input" name="friendbutton" placeholder="Username" required="" type="text">
                <button class="reset" type="reset">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </form>

        <center class="text">
            <div class="line">
                <h1 class='lineUp'>Add to Close Friends</h1>
            </div>
        </center>

        <center>
            <form class="form" method="POST" >
                <button>
                    <svg width="17" height="16" fill="none" xmlns="http://www.w3.org/2000/svg" role="img"
                        aria-labelledby="search">
                        <path d="M7.667 12.667A5.333 5.333 0 107.667 2a5.333 5.333 0 000 10.667zM14.334 14l-2.9-2.9"
                            stroke="currentColor" stroke-width="1.333" stroke-linecap="round" stroke-linejoin="round">
                        </path>
                    </svg>
                </button>
                <input class="input" name="closefriendbutton" placeholder="Username" required="" type="text">
                <button class="reset" type="reset">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </form>

<?php 
if ($_SERVER['REQUEST_METHOD'] === 'POST') 
{

        $db = get_pdo_connection();
        
        if (isset($_POST['friendbutton']) || isset($_POST['closefriendbutton'])) {       
            
            // Collect the username from the post request
            $username = isset($_POST['friendbutton']) ? $_POST['friendbutton'] : $_POST['closefriendbutton'];

            $query = $db->prepare("SELECT * FROM users WHERE username = ?");
            $query->execute([$username]);
            $result = $query->fetch(PDO::FETCH_ASSOC);

            if ($query->rowCount() === 0) {
                    echo $username . " does not exist on SparkSocial\n";
                    return;
            }
            
            // Check if they are already friends
            $query2 = $db->prepare("SELECT * FROM Canfriend WHERE (uID1 = ? AND uID2 = ?) OR (uID1 = ? AND uID2 = ?)");
            $query2->execute([$_SESSION['uID'], $result['uID'], $result['uID'], $_SESSION['uID']]);
            $result2 = $query2->fetch(PDO::FETCH_ASSOC);
            
            // if friends 
            if ($query2->rowCount() > 0 AND isset($_POST['closefriendbutton'])) {
                
                $stmt = $db->prepare("SELECT * FROM Canfriend WHERE uID1 = ? AND uID2 = ?");
                $stmt->execute([$result['uID'], $_SESSION['uID']]);
                $result3 = $stmt->fetch(PDO::FETCH_ASSOC);
                
                if($result3['isclosefriend'] == 1) {
                    echo "You are already close friends with " . $username;
                    return;
                }

                /// Prepare a SQL query to insert the user IDs and default values into the table
                $stmt = $db->prepare("UPDATE Canfriend SET isclosefriend = 1, isfriend = 1 WHERE uID1 = ? AND uID2 = ?");
                $stmt->execute([$result['uID'], $_SESSION['uID']]);
                echo $result['username'] . " is now a close friend\n";
            } // if not friends, add as a friend
            else if ($query2->rowCount() == 0 AND isset($_POST['friendbutton'])) {

                /// Prepare a SQL query to insert the user IDs and default values into the table
                $stmt = $db->prepare("INSERT INTO Canfriend (uID1, uID2, isclosefriend, isfriend) VALUES (?, ?, 0, 1)");
                $stmt->execute([$result['uID'], $_SESSION['uID']]); 
                echo "Friend request sent to " . $result['username'] . "\n"; 
            } 
            else {
                echo "You are already friends with " . $username;
            } 
        }
}
?>

        </center>

    </main>
</body>


</html>
