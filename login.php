<?php
session_start();

// Database connection settings
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "football_bets";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch form data
$email = $_POST['email'];

// Validate user input
if (empty($email)) {
    echo "Email is required.";
    exit();
}

 // Check if email exists in database
    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        die("MySQL prepare statement failed: " . $conn->error);
    }
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Email exists, login success
        $user = $result->fetch_assoc();
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['tickets'] = $user['tickets'];
        header("Location: bet.php");
        exit();
    } else {
        // Login failed
        echo '<h1 style="color: red;">อีเมลไม่ถูกต้อง</h1>';
    }
    $stmt->close();

$conn->close();
?>