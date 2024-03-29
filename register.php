<?php
// Check if user is logged in
require_once("config.php");
$db = get_pdo_connection();
if (isset($_SESSION['uID'])) {
    // Display logout and edit profile links
    $logout_link = "<a href='logout.php'>Logout</a>";
    $edit_profile_link = "<a href='edit-profile.php'>Edit Profile</a>";
} else {
    // Display register and login links
    $register_link = "<a href='register.php'>Register</a>";
    $login_link = "<a href='login.php'>Login</a>";
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
                <img src="images/pfp.png" width="57" height="57" />
                <div class="dropdown-menu">
                    <?php
                    // Output the appropriate links based on user login status
                    if (isset($logout_link) && isset($edit_profile_link)) {
                        echo $logout_link;
                        echo $edit_profile_link;
                    } else {
                        echo $register_link;
                        echo $login_link;
                    }
                    ?>
                </div>
            </div>
        </div>
        </div>



        <center class="text">
            <div class="line">
                <h1 class='lineUp'>Register here</h1>
            </div>
        </center>


    </main>
</body>

</html>

<?php


// Handle any inserts/updates/deletes before outputting any HTML
// INSERT 
if (isset($_POST["insert"]) && !empty($_POST["insert_data"])) {
    $dataToInsert = htmlspecialchars($_POST["insert_data"]);
    echo "inserting $dataToInsert ...";

    $db = get_pdo_connection();
    $query = $db->prepare("insert into Content (data) values (?)");
    $query->bindParam(1, $dataToInsert, PDO::PARAM_STR);
    if ($query->execute()) {
        header("Location: " . $_SERVER['PHP_SELF']);
    } else {
        echo "Error executing insert query:<br>";
        print_r($query->errorInfo());
    }
}

?>





<center>
    <?php
    $insert_form = new PhpFormBuilder();
    $insert_form->set_att("method", "POST");
    $insert_form->add_input("", array(
        "type" => "email",
        "required" => true,
        "placeholder" => "Enter Email"
    ), "email");
    $insert_form->add_input("", array(
        "type" => "password",
        "required" => true,
        "placeholder" => "Enter Password"
    ), "Password");
    $insert_form->add_input("", array(
        "type" => "text",
        "required" => true,
        "placeholder" => "Enter Username"
    ), "username");
    $insert_form->add_input("Insert", array(
        "type" => "submit",
        "value" => "Register"
    ), "insert");
    $insert_form->build_form();

    if (!empty($_POST)) {
        $email = $_POST["email"];
        $Password = $_POST["Password"];
        $username = $_POST["username"];

        // Check if username is already taken
        $query = $db->prepare("SELECT COUNT(*) as count FROM users WHERE username = :username");
        $query->bindParam(":username", $username, PDO::PARAM_STR);
        $query->execute();
        $result = $query->fetch(PDO::FETCH_ASSOC);
        $count = $result['count'];

        if ($count > 0) {
            echo "<center style='padding-top: 50px; color: red; text-shadow: 0 0 10px red; animation: glowing 2s infinite;'>Username already taken</center>";
        } else if (strlen($Password) < 8 || !preg_match("/[0-9]/", $Password)) {
            echo "<center style='padding-top: 50px; color: red; text-shadow: 0 0 10px red; animation: glowing 2s infinite;'>Invalid password. Password should be at least 8 characters and contain at least one number.</center>";
        } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo "<center style='padding-top: 50px; color: red; text-shadow: 0 0 10px red; animation: glowing 2s infinite;'>Invalid email address</center>";
        } else {

            $query2 = $db->prepare("INSERT INTO users (email, Password, username) VALUES (:email, :Password, :username)");
            $query2->bindParam(":email", $email, PDO::PARAM_STR);
            $hashed = password_hash($Password, PASSWORD_DEFAULT);
            $query2->bindParam(":Password", $hashed, PDO::PARAM_STR);
            $query2->bindParam(":username", $username, PDO::PARAM_STR);

            // Check if password is at least 8 characters and contains a number
    

            if ($query2->execute()) {
                echo "User successfully registered!";
            } else {
                echo "Error registering.";
            }
        }
    }

    exit();

    ?>
</center>