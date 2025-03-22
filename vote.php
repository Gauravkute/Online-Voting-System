<?php
session_start();
include('connect.php');

$votes = $_POST['cvotes'];
$total_votes = $votes + 1;
$cid = $_POST['cid'];
$uid = $_SESSION['userdata']['id'];

// 1. First, verify the candidate is actually a candidate (Role=2)
$check_candidate = mysqli_query($connect, "SELECT * FROM user WHERE id=$cid AND Role=2");
if(mysqli_num_rows($check_candidate) == 0) {
    echo "
    <script>
    alert('Error: Not a valid candidate!');
    window.location = '../routes/dashboard.php';
    </script>";
    exit;
}

// 2. Update candidate's vote count correctly
$update_votes = mysqli_query($connect, "UPDATE user SET Vote=$total_votes WHERE id=$cid");

// 3. Mark the user as having voted and store which candidate they voted for
$update_user_status = mysqli_query($connect, "UPDATE user SET Status=1, Vote=$cid WHERE id=$uid");

if($update_votes && $update_user_status) {
    // Get updated user data
    $user_query = mysqli_query($connect, "SELECT * FROM user WHERE id=$uid");
    $userData = mysqli_fetch_array($user_query);
    
    // Get updated candidate data
    $candidate = mysqli_query($connect, "SELECT * FROM user WHERE Role=2");
    $candidateData = mysqli_fetch_all($candidate, MYSQLI_ASSOC);

    $_SESSION['userdata'] = $userData;
    $_SESSION['candidatedata'] = $candidateData;

    echo "
    <script>
    alert('Voting Successful!!');
        window.location = '../routes/dashboard.php';
    </script>";
} else {
    echo "
    <script>
    alert('Some error occurred!!!');
        window.location = '../routes/dashboard.php';
    </script>";
}
?>