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



        <div class="wrapper2">
            <div class="dropdown">
                <img src="images/chat.png" width="57" height="57" />
                <div class="dropdown-menu">
                    <a href="edit-profile.php">Edit Profile</a>
                    <a href="register.php">Register</a>
                    <a href="login.php">Login</a>
                </div>
            </div>
        </div>
        </div>



        <center class="text">
            <div class="line">
                <h1 class='lineUp'>Public Feed</h1>
            </div>
        </center>

        <center style="padding-top: 75px;">

            <?php
            // Check if user is logged in
            
            if (isset($_SESSION['uID'])) {
                // Display logout and edit profile links
                echo '<button class="glowing-btn"><span class="glowing-txt">P<span class="faulty-letter">O</span>ST</span></button>';
                echo '<div style = "padding-bottom: 100px;"></div>';
            } else {

                // Customer cannot post or view posts
                echo "You must login in order to post.";

            }
            ?>




        </center>
        <center>
            <?php
            // Retrieve posts from the database
            $stmt = $db->prepare("SELECT c.text, u.username, u.profilepic 
            FROM Content c 
            INNER JOIN users u ON c.uID = u.uID 
            ORDER BY c.datecreated DESC
            ");
            $stmt->execute();
            $posts = $stmt->fetchAll(PDO::FETCH_ASSOC);

            ?>


            <div class="posts">
                <?php foreach ($posts as $post): ?>
                    <div class="post">

                        <div class="card-container">
                            <div class="card-header">
                                <div class="img-avatar">
                                    <img style="width: 50px;height: 50px; border-radius: 50%; margin-right: 14px;"
                                        src="images/<?= $post['profilepic'] ?>" alt="profile picture">
                                </div>
                                <div class="text-chat">
                                    <p>Posted by
                                        <?= $post['username'] ?>
                                    </p>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="messages-container">
                                    <div class="message-box left">
                                        <p>
                                            <?= $post['text'] ?>
                                        </p>
                                    </div>
                                    <div class="message-box right">
                                        <p>Test</p>
                                    </div>
                                </div>


                                <div class="message-input">
                                    <form>
                                        <textarea type="" class="message-send"
                                            placeholder="Type your message here"></textarea>
                                        <button type="" class="button-send">Comment</button>
                                    </form>
                                </div>
                            </div>
                        </div>





                        <?php if (isset($_SESSION['uID'])): ?>
                            <form method="post" action="create_comment.php">
                                <!-- not implemented yet -->
                                <input type="hidden" name="post_id" value="<?= $post['cID'] ?>">
                                <textarea name="comment_content" placeholder="Add a comment"></textarea>
                                <button type="submit">Comment</button>
                            </form>
                        <?php endif; ?>

                        <!-- <?php
                        // Retrieve comments for the post from the database
                        $stmt = $db->prepare("SELECT * FROM Content WHERE cID = ? ORDER BY created_at DESC");
                        $stmt->execute([$post['cID']]);
                        $comments = $stmt->fetchAll(PDO::FETCH_ASSOC);
                        ?> -->

                        <div class="comments">
                            <?php foreach ($comments as $comment): ?>
                                <div class="comment">
                                    <p>
                                        <?= $comment['cID'] ?>
                                    </p>
                                    <p>Commented by
                                        <?= $comment['uID'] ?>
                                    </p>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endforeach;
                ?>
            </div>

        </center>

    </main>
</body>

</html>