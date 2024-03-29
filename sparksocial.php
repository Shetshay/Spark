<?php
require_once("config.php");
require_once("comment.php");
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
                <h1 class='lineUp'>Public Feed</h1>
            </div>
        </center>

        <center>

            <?php
            // Check if user is logged in
            
            if (isset($_SESSION['uID'])) {
                // Display logout and edit profile links
                echo '<a href="post.php"><button class="buttonpost">Post now</button></a>';
                echo '<div style="padding-bottom: 100px;"></div>';
            } else {
                // Display login/signup prompt
                // header("Location: login.php");
                // exit;
                echo "You need to be logged in to post.";
            }
            ?>




        </center>
        <center style="padding-bottom: 100px;">
            <?php
            // Retrieve posts from the database
            $stmt = $db->prepare("SELECT c.cID, c.text, u.username, u.profilepic, c.datecreated, c.Media, c.level
                      FROM Content c 
                      INNER JOIN users u ON c.uID = u.uID 
                      WHERE c.level = '10'
                      ORDER BY c.datecreated DESC;");

            $stmt->execute();
            $posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
            ?>



            <div class="posts">
                <?php foreach ($posts as $post): ?>
                    <div class="post">

                        <div class="card-container">
                            <div class="post-date" style="text-align:center; color:#555;">
                                <?= $post['datecreated'] ?>
                            </div>
                            <div class="card-header">
                                <div class="img-avatar">
                                    <img style="width: 50px;height: 50px; border-radius: 50%; margin-right: 14px;"
                                        src="images/<?= $post['profilepic'] ?>" alt="profile picture">
                                </div>

                                <div class="text-chat">
                                    <p>Posted by
                                        <?= $post['username'] ?>
                                    </p>
                                    <div class="message-box left">
                                        <p>
                                            <?= $post['text'] ?>
                                        </p>
                                        <?php if ($post['Media']): ?>
                                            <?php
                                            $media_file = "media/" . $post['Media'];

                                            // Get the dimensions of the image
                                            list($width, $height, $type) = getimagesize($media_file);
                                            // This code uses the getimagesize() function to retrieve the dimensions and type of the image file. Then, depending on the image type, it uses the appropriate imagecreatefrom*() function to load the image into memory. Finally, it uses the appropriate image*() function to save the resized image to a new file.
                                            // If either dimension is greater than 200px, resize the image
                                            if ($width > 200 || $height > 200) {
                                                // Calculate the new dimensions
                                                $new_width = 200;
                                                $new_height = 200;
                                                if ($width > $height) {
                                                    $new_height = ($height / $width) * $new_width;
                                                } else {
                                                    $new_width = ($width / $height) * $new_height;
                                                }

                                                // Create a new image with the new dimensions
                                                switch ($type) {
                                                    case IMAGETYPE_JPEG:
                                                        $image = imagecreatefromjpeg($media_file);
                                                        break;
                                                    case IMAGETYPE_PNG:
                                                        $image = imagecreatefrompng($media_file);
                                                        break;
                                                    case IMAGETYPE_WEBP:
                                                        $image = imagecreatefromwebp($media_file);
                                                        break;
                                                    case IMAGETYPE_GIF:
                                                        $image = imagecreatefromgif($media_file);
                                                        break;
                                                    default:
                                                        throw new Exception("Unsupported image format");
                                                }
                                                $image_p = imagecreatetruecolor($new_width, $new_height);

                                                // Resize the image
                                                imagecopyresampled($image_p, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);

                                                // Save the resized image to a new file
                                                $new_file = "media/resized_" . $post['Media'];
                                                switch ($type) {
                                                    case IMAGETYPE_JPEG:
                                                        imagejpeg($image_p, $new_file);
                                                        break;
                                                    case IMAGETYPE_PNG:
                                                        imagepng($image_p, $new_file);
                                                        break;
                                                    case IMAGETYPE_WEBP:
                                                        imagewebp($image_p, $new_file);
                                                        break;
                                                    case IMAGETYPE_GIF:
                                                        imagegif($image_p, $new_file);
                                                        break;
                                                    default:
                                                        throw new Exception("Unsupported image format");
                                                }

                                                // Set the media file to the resized image
                                                $media_file = $new_file;
                                            }
                                            ?>
                                            <img src="<?= $media_file ?>" alt="Post Media">
                                        <?php endif; ?>


                                    </div>
                                </div>


                            </div>
                            <div class="card-body">
                                <div class="messages-container">
                                    <div class="message-box right">
                                        <p>Test comment.</p>
                                    </div>
                                </div>


                        

                                <?php if (isset($_SESSION['uID'])): ?>
                                    <div class="message-input">
                                        <form method="post" action='comment.php'>
                                            <input type="hidden" name="post_id" value="<?= $post['cID'] ?>">
                                            <input type="hidden" name="level" value="<?= $post['level'] ?>">
                                            <textarea name="comment_content" type="" class="message-send"
                                                placeholder="Type your message here"></textarea>
                                            <button type="submit" name='post_comment' class="button-send">Comment</button>
                                        </form>
                                    </div>
                                <?php endif; ?>



                            </div>
                        </div>







                        <!-- <?php
                        // Retrieve comments for the post from the database
                        $stmt = $db->prepare("SELECT * FROM Content WHERE pcID = ? ORDER BY created_at DESC");
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