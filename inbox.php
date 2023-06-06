<?php
require_once("config.php");
$db = get_pdo_connection();
?>

<!DOCTYPE html>
<html>

<head>
    <title>Spark Social</title>
    <link rel="stylesheet" type="text/css" href="inbox.css">
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
                <h1 class='lineUp'>Inbox</h1>
            </div>
        </center>


        <center>
            <?php
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                if (isset($_POST['accepted'])) {
                    accept_request($_POST['requestID'], $_POST['requestFrom'], $_POST['requestType']);
                } else {
                    decline_request($_POST['requestID']);
                }
            }
            function decline_request($requestID)
            {
                global $db;
                $delete_query = $db->prepare("DELETE FROM requests WHERE requestID = ?");
                $delete_query->execute([$requestID]);
            }

            function accept_request($requestID, $requesteruID, $requestType)
            {
                global $db;

                if ($requestType === "friend") {
                    $accept_query = $db->prepare("INSERT INTO Canfriend (uID1, uID2, isclosefriend, isfriend) VALUES (?,?,0,1)");
                    $accept_query->execute([$_SESSION['uID'], $requesteruID]);

                }
                if ($requestType == "close friend") {
                    $accept_close_query = $db->prepare("UPDATE Canfriend SET isclosefriend = 1, isfriend = 1 WHERE uID1 = ? AND uID2 = ? OR uID1 = ? AND uID2 = ?");
                    $accept_close_query->execute([$_SESSION['uID'], $requesteruID, $requesteruID, $_SESSION['uID']]);

                }

                decline_request($requestID);

            }

            function display_requests($requests, $requestCount)
            {
                global $db;
                $name_query = $db->prepare("SELECT username FROM users as u JOIN requests as r ON (u.uID = r.requestFrom) WHERE requestTo = ?");
                $name_query->execute([$_SESSION['uID']]);
                $name_results = $name_query->fetchAll(PDO::FETCH_ASSOC);

                for ($i = 0; $i < $requestCount; $i++) {


                    echo '<h1 style="font-size: smaller; margin-right: 12.5%;">' . $requests[$i]['requestType'] . ' request from ' . $name_results[$i]['username'] . '</h1>';

                    echo '
        <form method="post">
            <input type="hidden" name="requestID" value="' . $requests[$i]['requestID'] . '">
            <input type="hidden" name="requestFrom" value="' . $requests[$i]['requestFrom'] . '">
            <input type="hidden" name="requestType" value="' . $requests[$i]['requestType'] . '">
            <input type="hidden" name="accepted" value="' . "1" . '">
            <div style="display: flex; justify-content: center;">
            <button class="buttonpost">Accept Request</button></a>
            <button class="buttonpost2">Deny Request</button></a>
            </div>
        </form>
        ';
                }
            }


            $db = get_pdo_connection();

            $friend_request_query = $db->prepare("SELECT * FROM requests WHERE requestTo  = ?");
            $friend_request_query->execute([$_SESSION['uID']]);

            $friend_request_result = $friend_request_query->fetchAll(PDO::FETCH_ASSOC);
            $requestCount = $friend_request_query->rowCount();
            if ($requestCount > 0) {

                display_requests($friend_request_result, $requestCount);
            } else {
                echo "There are currently no requests available";
            }

            ?>

        </center>

    </main>
</body>

</html>
