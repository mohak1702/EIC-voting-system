<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $password = $_POST["password"];
// Database connection configuration
$servername = "localhost";
$username = "root";
$password = "";
$database = "voting";

// Create a connection to the database
$conn = new mysqli($servername, $username, $password, $database);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the form has been submitted

    
    // Query the database to check for the user's credentials
    $sql = "SELECT * FROM register WHERE email = '$email' AND password = '$password'";
    $result = $conn->query($sql);
    
    if ($result->num_rows == 1) {
        // Authentication successful
        session_start();
        $_SESSION["email"] = $email;
        
        // Redirect to a protected page or homepage
        header('Location: login_invalid.html');
        exit();
    } else {
        header('Location: login_valid.html');
    }
}

$conn->close();
?>

