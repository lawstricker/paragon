<?php
include 'db_connect.php';

// Get form data
$email = $_POST['email'];
$password = $_POST['password'];
if (empty($email) || empty($password)) {
    echo "Email and password are required.";
    exit();
}

// SQL query to insert data
$res = authenticateUser($email, $password);
if ($res) {
  header("Location: index.php");
} else {
   header("Location: login.html");
}
?>