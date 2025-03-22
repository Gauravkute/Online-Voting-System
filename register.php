<?php
include("connect.php");

$name = $_POST["username"];
$address = $_POST["Address"];  
$email = $_POST["email"];      
$number = $_POST["Number"];
$password = $_POST["password"];
$cpassword = $_POST["cpassword"]; 
$image = $_FILES["image"]["name"];
$temp_name = $_FILES["image"]["tmp_name"]; 
$role = ($_POST["role"] == "voter") ? 1 : 2;

if($password == $cpassword) {
    move_uploaded_file($temp_name, "../uploads/$image");
    
    $insert = mysqli_query($connect, "INSERT INTO user (Name,Address,Email,Number,Password,Photo,Role, Status, Vote) 
                                     VALUES ('$name', '$address', '$email', '$number', '$password', '$image', '$role', 0, 0)");
    
    if($insert) {
        echo "
        <script>
            alert('Registration Successful!!'); 
            window.location = '../index.html'; 
        </script>";
    } else {
        echo "
        <script>
            alert('Some error occurred'); 
            window.location = '../routes/register.html';
        </script>";
    }
} else {
    echo "
    <script>
        alert('Password and confirm password do not match'); 
        window.location = '../routes/register.html'; 
    </script>";
}
?>