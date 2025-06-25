<?php
$host = 'localhost';
$dbname = 'paragon';
$username = 'root';
$password = '';

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

function registerUser($name, $email, $password) {
    global $conn;
    $hashed_password = password_hash(trim($password), PASSWORD_DEFAULT);
    $stmt = $conn->prepare("INSERT INTO users (fullName, email, password) VALUES (?, ?, ?)");
    return $stmt->execute([$name, $email, $hashed_password]);
}

function authenticateUser($email, $password) {
    try {
        global $conn;

    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ? LIMIT 1");
    $stmt->execute([$email]);

    // Ensure we fetch associative array
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    
    if ($user) {
        $password = 'password';
        $hash = password_hash($password, PASSWORD_DEFAULT);

        if (password_verify($password, $hash)) {
            echo "✅ Password verified.";
        } else {
            echo "❌ Verification failed.";
        }
        var_dump($password, $user['password']);
        die();
        if (password_verify(trim($password), trim($user['password']))) {
            $_SESSION['fullName'] = $user['fullName'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['loggedin'] = true;
            return true;
        }
    }
    return false;
    // return false;
    } catch (\Throwable $th) {
        throw $th;
    }
}
?>