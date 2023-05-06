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
    <title>Friends</title>
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



        <center class="text">
            <div class="line">
                <h1 class='lineUp'>Friend Feed</h1>
            </div>
        </center>

        <center style="padding-top: 75px;">

            <?php
            // Check if user is logged in
            
            if (isset($_SESSION['uID'])) {
                // Display logout and edit profile links
                echo '<center><div class="container"><a class="button" href="#" style="--color:#ff1867;">
                        <span></span>
                        <span></span>
                        <span></span>
                        <span></span>
                        Post
                    </a>
            </center>';
            } else {

                // Customer cannot post or view posts
                echo "You must login in order to post.";
            }


            // Check if user is logged in
            if (!isset($_SESSION['uID'])) {
                // Redirect to login page
                header("Location: login.php");
                exit;
            }
            echo '<div style="padding-top: 75px;"> </div>';
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                // Get post content from the form
                $post_content = $_POST['cID'];

                // Insert post into database
                $stmt = $db->prepare("INSERT INTO Content (uID, cID) VALUES (?, ?)");
                $stmt->execute([$_SESSION['uID'], $post_content]);

                // Redirect back to friends page
                header("Location: friends.php");
                exit;
            }

            ?>

            <?php
            $user_id = $_SESSION['uID'];
            echo $_SESSION['uID'];
            // Retrieve posts from the database
            
            $stmt = $db->prepare("SELECT * FROM Content NATURAL JOIN users NATURAL JOIN
(
    SELECT uID2 as uID
    FROM Canfriend
    WHERE uID1 = $user_id

    UNION
    SELECT uID1 as uID
    FROM Canfriend
    WHERE uID2 = $user_id
) as fc
WHERE level = '20';");
            $stmt->execute();
            $posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
            ?>

            <div class="posts">
                <?php foreach ($posts as $post): ?>
                    <div class="post">
                        <p>
                            <?= $post['text'] ?>
                        </p>

                        <p>Posted by
                            <?= $post['username'] ?>
                        </p>

                        <?php if (isset($_SESSION['uID'])): ?>
                            <form method="post" action="create_comment.php">
                                <input type="hidden" name="post_id" value="<?= $post['cID'] ?>">
                                <textarea name="comment_content" placeholder="Add a comment"></textarea>
                                <button type="submit">Comment</button>
                            </form>
                        <?php endif; ?>

                        <?php
                        // Retrieve comments for the post from the database
                        $stmt = $db->prepare("SELECT * FROM Content WHERE cID = ? ORDER BY created_at DESC");
                        $stmt->execute([$post['cID']]);
                        $comments = $stmt->fetchAll(PDO::FETCH_ASSOC);
                        ?>

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
                <?php endforeach; ?>
            </div>

        </center>

    </main>
</body>

</html>