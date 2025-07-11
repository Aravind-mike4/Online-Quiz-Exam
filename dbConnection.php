<?php
require 'vendor/autoload.php';

use MongoDB\Client;

try {
    $client = new Client("mongodb://localhost:27017");
    $db = $client->selectDatabase("project");
} catch (MongoDB\Driver\Exception\Exception $e) {
    die("MongoDB connection failed: " . $e->getMessage());
}
?>
