<?php

$username = "root";
$password = "";
try {
    $conn = new PDO("mysql:host=localhost;dbname=auto_project", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}catch(PDOException $e){
    echo "Connection failed: " . $e->getMessage();
}