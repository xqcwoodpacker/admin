<?php
$dbhost = 'localhost';
$dbuser = 'root';
$dbpass = '';
$dbname = 'femcarefertility';
try {
    $conn = new mysqli($dbhost, $dbuser, $dbpass, $dbname);
} catch (Exception $e) {
    echo 'could not connect to database!';
    exit;
}

// $name='admin';
// $pass='admin123';
// $password_hash=password_hash($pass,PASSWORD_DEFAULT);
// $q="insert into admin_details(name,pass) values(?,?)";

// $stmt = $con->prepare($q);
// $stmt->bind_param('ss', $name, $password_hash);
// $stmt->execute();
