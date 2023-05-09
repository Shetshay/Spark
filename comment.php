<?php
require_once('./config.php');
$db = get_pdo_connection();

if (isset($_POST['post_comment'])) {
    $comment_content = $_POST['comment_content'];
    $parent_id = $_POST['post_id'];
    $level = $_POST['level'];
    $user_id = $_SESSION['uID'];

    echo $level;

    $stmt = $db->prepare("INSERT INTO Content (uID, text, level, pcID) VALUES (:uID, :text, :level, :pcID)");
    $stmt->bindValue(':uID', $user_id, PDO::PARAM_INT);
    $stmt->bindValue(':text', $comment_content, PDO::PARAM_STR);
    $stmt->bindValue(':pcID', $parent_id, PDO::PARAM_INT);
    $stmt->bindValue(':level', $level, PDO::PARAM_INT);
    $stmt->execute();
    if ($level == 10) {
        header("Location: sparksocial.php");
    }
    if ($level == 20) {
        header("Location: friends.php");
    }
    if ($level == 30) {
        header("Location: closefriends.php");
    }
    echo $level;
}

?>