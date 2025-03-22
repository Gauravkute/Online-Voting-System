<?php
session_start();
include("connect.php");

$email = $_POST["email"];
$password = $_POST["password"];
$role = ($_POST["role"] == "voter") ? 1 : 2;

$check = mysqli_query($connect, "SELECT * FROM user WHERE Email='$email' AND Password='$password' AND Role='$role'");

if(mysqli_num_rows($check) > 0) {
    $userData = mysqli_fetch_array($check);
    $candidate = mysqli_query($connect, "SELECT * FROM user WHERE Role=2");
    $candidateData = mysqli_fetch_all($candidate, MYSQLI_ASSOC);
    
    $_SESSION['userdata'] = $userData;
    $_SESSION['candidatedata'] = $candidateData;
    
    echo "
    <script>
        window.location = '../routes/dashboard.php';
    </script>";
} else {
    echo "
    <script>
        alert('Invalid credentials or user not found');
        window.location = '../index.html';
    </script>";
}
?>