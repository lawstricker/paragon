<?php
include 'db_connect.php';

// Get form data
$name = $_POST['name'];
$email = $_POST['email'];
$password = $_POST['password']; // Hash the password

// SQL query to insert data
$res = registerUser($name, $email, $password);

if ($res) {
  echo "Registration successful!";
  header("Location: login.html");
  exit();
} else {
  echo "Error:";
}
?>