<?php
require_once("config.php");

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

// UPDATE
if (
    isset($_POST["update"])
    && !empty($_POST["update_data"])
    && !empty($_POST["update_id"])
) {
    $dataToUpdate = htmlspecialchars($_POST["update_data"]);
    $idToUpdate = htmlspecialchars($_POST["update_id"]);
    echo "updating $dataToUpdate ...";

    $db = get_pdo_connection();
    $query = $db->prepare("update Content set data= ? where uID = ?");
    $query->bindParam(1, $dataToUpdate, PDO::PARAM_STR);
    $query->bindParam(2, $idToUpdate, PDO::PARAM_INT);
    if ($query->execute()) {
        header("Location: " . $_SERVER['PHP_SELF']);
    } else {
        echo "Error executing update query:<br>";
        print_r($query->errorInfo());
    }
}

// DELETE
if (isset($_POST["delete"])) {

    echo "deleting...<br>";

    $db = get_pdo_connection();
    $query = false;

    if (!empty($_POST["delete_id"])) {
        echo "deleting by id...";
        $query = $db->prepare("delete from Content where uID = ?");
        $query->bindParam(1, $_POST["delete_id"], PDO::PARAM_INT);
    } else if (!empty($_POST["delete_data"])) {
        echo "deleting by data...";
        $query = $db->prepare("delete from Content where data = ?");
        $query->bindParam(1, $_POST["delete_data"], PDO::PARAM_STR);
    }
    if ($query) {
        if ($query->execute()) {
            $_SESSION["affected_rows"] = $query->rowCount();
            header("Location: " . $_SERVER["PHP_SELF"]);
        } else {
            echo "Error executing delete query:<br>";
            print_r($query->errorInfo());
        }

    } else {
        echo "Unable to delete: no id uID data specified<br>";
    }
}
?>

<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>
        <?= $PROJECT_NAME ?>
    </title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <h1>
        <?= $PROJECT_NAME ?>
    </h1>

    <?php
    if (!empty($_SESSION["affected_rows"])) {
        echo "Deleted " . $_SESSION["affected_rows"] . " rows";
        unset($_SESSION["affected_rows"]);
    }
    ?>

    <h2>SQL SELECT -> HTML Table using <a href="https://www.php.net/manual/en/book.pdo.php">PDO</a></h2>
    <?php

    $db = get_pdo_connection();
    $query = $db->prepare("SELECT * FROM users"); //Add something here to say "While user has more than 2 posts.
    $query->execute();
    $rows = $query->fetchAll(PDO::FETCH_ASSOC);

    echo makeTable($rows);
    ?>

    <h2>SQL SELECT using input from form</h2>
    <?php
    $select_form = new PhpFormBuilder();
    $select_form->set_att("method", "POST");
    $select_form->add_input("uID to search for", array(
        "type" => "number"
    ), "search_id");
    $select_form->add_input("data to search for", array(
        "type" => "text"
    ), "search_data");
    $select_form->add_input("Submit", array(
        "type" => "submit",
        "value" => "Search"
    ), "search");
    $select_form->build_form();

    if (isset($_POST["search"])) {
        echo "searching...<br>";

        $db = get_pdo_connection();
        $query = false;

        if (!empty($_POST["search_id"])) {
            echo "searching by id...";
            $query = $db->prepare("select * from users where uID = :id");
            $query->bindParam(":id", $_POST["search_id"], PDO::PARAM_INT);
        } else if (!empty($_POST["search_data"])) {
            echo "searching by data...";
            $query = $db->prepare("select * from Content where cID = :data");
            $query->bindParam(":data", $_POST["search_data"], PDO::PARAM_STR);
        }
        if ($query) {
            if ($query->execute()) {
                $rows = $query->fetchAll(PDO::FETCH_ASSOC);
                echo makeTable($rows);
            } else {
                echo "Error executing select query:<br>";
                print_r($query->errorInfo());
            }

        } else {
            echo "Error executing select query: no uID or data specified<br>";
        }
    }
    ?>



    <h2>SQL SELECT using input and date join >= 2021-07-04 from form</h2>
    <?php
    $select_form = new PhpFormBuilder();
    $select_form->set_att("method", "POST");
    $select_form->add_input("uID to search for", array(
        "type" => "number"
    ), "search_id");
    $select_form->add_input("data to search for", array(
        "type" => "text"
    ), "search_data");
    $select_form->add_input("Submit", array(
        "type" => "submit",
        "value" => "Search"
    ), "search");
    $select_form->build_form();

    if (isset($_POST["search"])) {
        echo "searching...<br>";

        $db = get_pdo_connection();
        $query = false;

        if (!empty($_POST["search_id"])) {
            echo "searching by id...";
            $query = $db->prepare("select * from users where uID = :id and datejoin >= '2021-07-04'");
            $query->bindParam(":id", $_POST["search_id"], PDO::PARAM_INT);
        } else if (!empty($_POST["search_data"])) {
            echo "searching by data...";
            $query = $db->prepare("select * from Content where cID = :data and datejoin >= '2021-07-04'");
            $query->bindParam(":data", $_POST["search_data"], PDO::PARAM_STR);
        }

        if ($query) {
            if ($query->execute()) {
                $rows = $query->fetchAll(PDO::FETCH_ASSOC);
                echo makeTable($rows);
            } else {
                echo "Error executing select query:<br>";
                print_r($query->errorInfo());
            }

        } else {
            echo "Error executing select query: no uID or data specified<br>";
        }
    }



    ?>


    <h2>SQL INSERT using input from form</h2>

    <?php
    $insert_form = new PhpFormBuilder();
    $insert_form->set_att("method", "POST");
    $insert_form->add_input("uID", array(
        "type" => "number",
        "required" => true
    ), "uID");
    $insert_form->add_input("email", array(
        "type" => "email",
        "required" => true
    ), "email");
    $insert_form->add_input("Plaintext", array(
        "type" => "Plaintext",
        "required" => true
    ), "Plaintext");
    $insert_form->add_input("username", array(
        "type" => "text",
        "required" => true
    ), "username");
    $insert_form->add_input("datejoin", array(
        "type" => "date",
        "required" => true
    ), "datejoin");
    $insert_form->add_input("bio", array(
        "type" => "text",
        "required" => false
    ), "bio");
    $insert_form->add_input("profilepic", array(
        "type" => "text",
        "required" => false
    ), "profilepic");
    $insert_form->add_input("Insert", array(
        "type" => "submit",
        "value" => "Insert"
    ), "insert");
    $insert_form->build_form();

    if (!empty($_POST)) {
        $uID = $_POST["uID"];
        $email = $_POST["email"];
        $Plaintext = $_POST["Plaintext"];
        $username = $_POST["username"];
        $datejoin = $_POST["datejoin"];
        $bio = $_POST["bio"];
        $profilepic = $_POST["profilepic"];

        $query = $db->prepare("INSERT INTO users (uID, email, Plaintext, username, datejoin, bio, profilepic) VALUES (:uID, :email, :Plaintext, :username, :datejoin, :bio, :profilepic)");
        $query->bindParam(":uID", $uID, PDO::PARAM_INT);
        $query->bindParam(":email", $email, PDO::PARAM_STR);
        $query->bindParam(":Plaintext", $Plaintext, PDO::PARAM_STR);
        $query->bindParam(":username", $username, PDO::PARAM_STR);
        $query->bindParam(":datejoin", $datejoin, PDO::PARAM_STR);
        $query->bindParam(":bio", $bio, PDO::PARAM_STR);
        $query->bindParam(":profilepic", $profilepic, PDO::PARAM_STR);

        if ($query->execute()) {
            echo "Data inserted successfully!";
        } else {
            echo "Error inserting data.";
        }
    }
    ?>








    <h2>SQL UPDATE email using input uID from form</h2>

    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Get the form data
        $uID = $_POST["uID"];
        $email = $_POST["email"];

        // Connect to the database
        $conn = mysqli_connect("localhost", "sparksocial", "laicoskraps3420S23", "sparksocial");

        // Update the email in the database
        $sql = "UPDATE users SET email='$email' WHERE uID=$uID";
        mysqli_query($conn, $sql);

        // Close the database connection
        mysqli_close($conn);
    }

    $update_form = new PhpFormBuilder();
    $update_form->set_att("method", "POST");
    $update_form->add_input("uID to update data for", array(
        "type" => "number"
    ), "uID");
    $update_form->add_input("New Email", array(
        "type" => "email"
    ), "email");
    $update_form->add_input("Update", array(
        "type" => "submit",
        "value" => "Update"
    ), "update");
    $update_form->build_form();
    ?>














    <h2>SQL DELETE using input from form if USERJOIN < 2021-08-03</h2>

            <?php
            $delete_form = new PhpFormBuilder();
            $delete_form->set_att("method", "POST");
            $delete_form->add_input("uID to delete", array(
                "type" => "text"
            ), "delete_uid");
            $delete_form->add_input("Delete", array(
                "type" => "submit",
                "value" => "Delete"
            ), "delete");
            $delete_form->build_form();

            if (isset($_POST['delete'])) {
                $db = get_pdo_connection();
                if (!empty($_POST["delete_uid"])) {
                    echo "searching by id...";
                    $query = $db->prepare("DELETE FROM Canfriend where uID1 = ? or uID2 = ?");
                    $query->bindParam(1, $_POST["delete_uid"]);
                    $query->bindParam(2, $_POST["delete_uid"]);

                    $query->execute();
                    $db2 = get_pdo_connection();
                    $query2 = $db2->prepare("DELETE FROM users where uID = ? ");
                    $query2->bindParam(1, $_POST["delete_uid"]);
                    $query2->execute();
                }
            }
            ?>