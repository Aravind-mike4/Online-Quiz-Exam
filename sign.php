<?php
include_once 'dbConnection.php';

$name = $_POST['name'];
$gender = $_POST['gender'];
$college = $_POST['college'];
$email = $_POST['email'];
$mob = $_POST['mob'];
$password = $_POST['password'];
$cpassword = $_POST['cpassword'];

$name = htmlspecialchars($name);
$gender = htmlspecialchars($gender);
$college = htmlspecialchars($college);
$email = htmlspecialchars($email);
$mob = htmlspecialchars($mob);

if ($password != $cpassword) {
    header("location:index.php?q7=Password and Confirm Password do not match");
    exit();
}

// Hash the password securely
$hashedPassword = password_hash($password, PASSWORD_BCRYPT);

try {
    $usersCollection = $db->users;

    // Check if email already exists
    $existingUser = $usersCollection->findOne(['email' => $email]);
    if ($existingUser) {
        header("location:index.php?q7=Email already registered");
        exit();
    }

    $result = $usersCollection->insertOne([
        'name' => $name,
        'gender' => $gender,
        'college' => $college,
        'email' => $email,
        'mobile' => $mob,
        'password' => $hashedPassword // Store the securely hashed password
    ]);

    if ($result->getInsertedCount() == 1) {
        session_start();
        $_SESSION["name"] = $name;
        $_SESSION["email"] = $email;
        header("location:account.php?q=1");
    } else {
        header("location:index.php?q7=Registration failed");
    }
} catch (MongoDB\Driver\Exception\Exception $e) {
    header("location:index.php?q7=Database Error: " . urlencode($e->getMessage()));
} catch (Exception $e) {
    header("location:index.php?q7=An unexpected error occurred: " . urlencode($e->getMessage()));
}
?>
