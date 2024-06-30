<?php
session_start();

// Redirect to login if not logged in
if (!isset($_SESSION['email'])) {
    header("Location: login.html");
    exit();
}

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

// Process the bet
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['betstest'])) {
    $email = $_SESSION['email'];
    $total_bet = 0;
    $bets = $_POST['betstest'];
    
    foreach ($bets as $team_name => $bet_amount) {
        $bet_amount = (int)$bet_amount;
        if ($bet_amount > 0) {
            $total_bet += $bet_amount;
        }
    }

    if ($total_bet > $_SESSION['tickets']) {
        echo "You do not have enough tickets.";
        exit();
    }

    foreach ($bets as $team_name => $bet_amount) {
        $bet_amount = (int)$bet_amount;
        if ($bet_amount > 0) {
            // Insert or update bet in bets table
            $sql = "INSERT INTO betstest (email, team_name, bet_amount) VALUES (?, ?, ?)
            ON DUPLICATE KEY UPDATE bet_amount = bet_amount + ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssii", $email, $team_name, $bet_amount, $bet_amount);
            $stmt->execute();
        }
    }

    // Update user's tickets
    $new_tickets = $_SESSION['tickets'] - $total_bet;
    $sql = "UPDATE users SET tickets = ? WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("is", $new_tickets, $email);
    $stmt->execute();

    // Update session tickets
    $_SESSION['tickets'] = $new_tickets;

    // Redirect back to bet form
    header("Location: bet.php");
    exit();
}

$conn->close();
?>
