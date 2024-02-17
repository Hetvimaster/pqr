<?php
// Connect to your database (replace with your database details)
$servername = "localhost";
$username = "username";
$password = "password";
$dbname = "affectionate_acroama";

$conn = new mysqli('localhost', 'root', '', 'affectionate_acroama');
if($conn->connect_error){
    die('Connection Failed : '.$conn->connect_error);
}
// Retrieve user input
$username = $_POST['username'];
$password = $_POST['password'];

// Query to check user credentials
$sql = "SELECT * FROM sign_in WHERE username='$username' AND password='$password'";
$result = $conn->query($sql);

// Check if user exists
if ($result->num_rows > 0) {
    echo "Login successful!";
} else {
    echo "Invalid username or password.";
}

$conn->close();
?>