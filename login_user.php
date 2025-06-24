<?php
include 'db_connect.php';

// Get form data
$email = $_POST['email'];
$password = $_POST['password'];
if (empty($email) || empty($password)) {
    echo "Email and password are required.";
    exit();
}
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