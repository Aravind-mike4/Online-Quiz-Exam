<?php
include_once 'dbConnection.php';

$ref = @$_GET['q'];
$name = $_POST['name'];
$email = $_POST['email'];
$subject = $_POST['subject'];
$feedback = $_POST['feedback'];

$name = htmlspecialchars($name);
$email = htmlspecialchars($email);
$subject = htmlspecialchars($subject);
$feedback = htmlspecialchars($feedback);

$currentDateTime = new MongoDB\BSON\UTCDateTime(); // MongoDB's way to store current date/time

try {
    $feedbackCollection = $db->feedback;

    $result = $feedbackCollection->insertOne([
        'name' => $name,
        'email' => $email,
        'subject' => $subject,
        'message' => $feedback,
        'date' => $currentDateTime
    ]);

    if ($result->getInsertedCount() == 1) {
        header("location:$ref?q=Thank you for your valuable feedback");
    } else {
        header("location:$ref?w=Failed to submit feedback");
    }
} catch (MongoDB\Driver\Exception\Exception $e) {
    header("location:$ref?w=Database Error: " . urlencode($e->getMessage()));
} catch (Exception $e) {
    header("location:$ref?w=An unexpected error occurred: " . urlencode($e->getMessage()));
}
?>
