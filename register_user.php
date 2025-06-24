<?php
include 'db_connect.php';

// Get form data
$name = $_POST['name'];
$email = $_POST['email'];
$password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash the password

// SQL query to insert data
$sql = registerUser($name, $email, $password);

if ($sql) {
  echo "Registration successful!";
  header("Location: registration.html");
  exit();
} else {
  echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>