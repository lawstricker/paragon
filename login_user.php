<?php
include 'db_connect.php';

// Get form data
$name = $_POST['name'];
$email = $_POST['email'];
$password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash the password

// SQL query to insert data
$sql = authenticateUser($email, $password);

if ($sql) {
  echo "Login successful!";
  header("Location: index.php");
  exit();
} else {
  echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>