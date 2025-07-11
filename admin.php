<?php
session_start();

if (isset($_SESSION['email'])) {
    session_unset();
}

include_once 'dbConnection.php';

$ref = @$_GET['q'];
$email = $_POST['uname'];
$password = $_POST['password'];

$email = htmlspecialchars($email);

try {
    $adminsCollection = $db->admins;

    $admin = $adminsCollection->findOne([
        'email' => $email,
        'password' => $password
    ]);

    if ($admin) {
        $_SESSION["name"] = 'Aravind';
        $_SESSION["key"] = 'admin';
        $_SESSION["email"] = $email;
        header("location:dash.php?q=0");
    } else {
        header("location:$ref?w=Warning : Access denied");
    }
} catch (MongoDB\Driver\Exception\Exception $e) {
    header("location:$ref?w=Database Error: " . urlencode($e->getMessage()));
} catch (Exception $e) {
    header("location:$ref?w=An unexpected error occurred: " . urlencode($e->getMessage()));
}
?>
