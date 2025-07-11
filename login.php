<?php
session_start();

if (isset($_SESSION["email"])) {
    session_destroy();
}

include_once 'dbConnection.php';

$ref = @$_GET['q'];

$email = $_POST['email'];
$password = $_POST['password'];

$email = htmlspecialchars($email);

$hashedPassword = md5($password);

try {
    $usersCollection = $db->users;

    $user = $usersCollection->findOne([
        'email' => $email,
        'password' => $hashedPassword
    ]);

    if ($user) {
        $_SESSION["name"] = $user['name'];
        $_SESSION["email"] = $user['email'];

        header("location:account.php?q=1");
    } else {
        header("location:$ref?w=Wrong Username or Password");
    }
} catch (MongoDB\Driver\Exception\Exception $e) {
    header("location:$ref?w=Database Error: " . urlencode($e->getMessage()));
} catch (Exception $e) {
    header("location:$ref?w=An unexpected error occurred: " . urlencode($e->getMessage()));
}
?>
