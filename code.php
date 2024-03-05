<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $num = $_POST['number'];
    $email = $_POST['email'];
    $pass = $_POST['pass'];

    // Create a connection to the database (using mysqli for improved security)
    $mysqli = new mysqli('localhost', 'root', '', 'voting');

    // Check for database connection errors
    if ($mysqli->connect_error) {
        die('Database connection error: ' . $mysqli->connect_error);
    }

    // Insert data into the 'register' table
    $query = "INSERT INTO register (number, email, password) VALUES (?, ?, ?)";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param('sss', $num, $email, $pass);
    $stmt->execute();

    // Check if the insertion was successful
    if ($stmt->affected_rows > 0) {
        // Redirect to the login page
        header('Location: successfull.html');
        exit(); // Make sure to exit after the header redirect
    } else {
        echo "Failed to insert data into the database.";
    }

    // Close the database connection
    $mysqli->close();
} else {
    // Handle invalid HTTP method (GET, etc.)
    echo "Invalid request method. Please submit the form using POST.";
}

// Fast2SMS API key
$api_key = 'NjQzMDRmNTY2NjZjNjI1NDU5NzU2MzMxNDM2NjQ0NzM=';

// Generate OTP
$otp = rand(1000, 9999); // You can customize the OTP generation logic if needed

// Store OTP in session for verification
session_start();
$_SESSION['otp'] = $otp;

// User input
$to_number = $_POST['number']; // Assuming you have a form field for phone number input

// API endpoint
$url = " https://api.textlocal.in/send/";

// SMS data
$sms_data = array(
    'authorization' => $api_key,
    'sender_id' => 'EIC', // Replace with your sender ID
    'message' => "Your OTP is: $otp",
    'route' => 'otp',
    'numbers' => $to_number,
);

// Initialize cURL
$ch = curl_init();

// Set cURL options
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($sms_data));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

// Execute cURL request
$response = curl_exec($ch);

// Check for errors
if ($response === false) {
    echo "Error: " . curl_error($ch);
} else {
    // Decode response
    $result = json_decode($response, true);

    // Check if SMS sent successfully
    if ($result['return'] == true) {
        echo "OTP sent successfully.";
    } else {
        echo "Failed to send OTP. Error: " . $result['message'];
    }
}

// Close cURL session
curl_close($ch);
?>

?>
