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
                <input class="input" name="username" placeholder="Username" required="" type="text">
                <button class="reset" type="reset">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </form>
<?php 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

// Make sure that the person receiving the request is already your friend 

        $username = "";
        // Check if the form data is not empty
        if (isset($_POST['username'])) {
            // The form was filled out
            $db = get_pdo_connection();
            
            // Check if the username exists 
            $username = $_POST['username'];


            $query = $db->prepare("SELECT * FROM users WHERE username = ?");
            $query->execute([$username]);
            $result = $query->fetch(PDO::FETCH_ASSOC);

            // The username exists
            if ($query->rowCount() > 0) {
               // echo "This is the row count " . $query->rowCount();
                // The user exists in the database
               // echo "User " . $username . " exists!";
                // Access the user's ID, email, or other fields from the $result array
               // echo "The response from the database username is: " . $result['username'];
               // echo "The response from the database username is: " . $result['uID'];
                
                // Prepare a SQL query to check if the two user IDs exist in the isfriend column
                $query2 = $db->prepare("SELECT * FROM Canfriend WHERE (uID1 = ? AND uID2 = ?) OR (uID1 = ? AND uID2 = ?)");
                $query2->execute([$_SESSION['uID'], $result['uID'], $result['uID'], $_SESSION['uID']]);
                $result2 = $query2->fetch(PDO::FETCH_ASSOC);

                // Check if the query returned any rows
                if ($query2->rowCount() > 0) {
                    // The two user IDs are friends, do nothing
                    echo "We are here and we have a match betwen " . $_SESSION['username'] . " and " . $result['username'] . ". so we do not need to send them a friend request\n";
                    //echo "The two users which are " . $_SESSION['username']; . " and " . $result['username'] . " are already friends"; 
                } else {
                    echo "Since you are not friends with " . $result['username'] . " we are sending them a friend request\n"; 
                    /// Prepare a SQL query to insert the user IDs and default values into the table
                    $stmt = $db->prepare("INSERT INTO Canfriend (uID1, uID2, isclosefriend, isfriend) VALUES (?, ?, 1, 1)");
                    $stmt->execute([$result['uID'], $_SESSION['uID']]);
                }

            } else {
                
                // The user does not exist in the database
                echo "User " . $username . " does not exist.";
            }

        } else {
            // The form was not filled out
            echo "Please fill out the form";
        }

}
?>

        </center>

    </main>
</body>


</html>
