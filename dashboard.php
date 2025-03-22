<?php
session_start();
if (!isset($_SESSION['userdata'])) {
    header("location: ../");
}
$userdata = $_SESSION['userdata'];

// Fix: Check if candidatedata exists in session and is properly named
if(isset($_SESSION['candidatedata'])) {
    $candidatedata = $_SESSION['candidatedata'];
} else {
    // Fetch candidate data if not in session - ONLY get users with Role=2 (candidates)
    include("../api/connect.php");
    $candidate = mysqli_query($connect, "SELECT * FROM user WHERE Role=2");
    $candidatedata = mysqli_fetch_all($candidate, MYSQLI_ASSOC);
    $_SESSION['candidatedata'] = $candidatedata;
}

// Fix: Correct status check syntax
if($userdata['Status'] == 0) {
    $status = '<b style="color:red">Not Voted</b>';
} else {
    $status = '<b style="color:green">Voted</b>';
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #8e44ad;
            margin: 0;
            padding: 0;
        }

        .header {
            font-family: 'Courier New', Courier, monospace;
            padding: 30px;
            text-align: center;
            background: #8e44ad;
            color: white;
            font-size: 30px;
            width: 80%;
        }

        .Profile {
            border: none;
            border-radius: 10px;
            background-color: white;
            width: 350px;
            padding: 20px;
            float: left;
        }

        .candidate {
            border: none;
            border-radius: 10px;
            background-color: white;
            width: 1000px;
            padding: 20px;
            float: right;
            margin-right: 50px;
        }

        button {
            font-family: 'Franklin Gothic Medium', 'Arial Narrow', Arial, sans-serif;
            background-color: rgb(17, 91, 165);
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 4px;
            cursor: pointer;
            width: 40%;
            margin-bottom: 10px;
            text-align: center;
        }

        button:hover {
            background-color: #45a049;
        }

        #text {
            font-family: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;
            font-size: 17px;
            font-weight: bold;
        }

        #maintext {
            font-family: Verdana, Geneva, Tahoma, sans-serif;
            font-size: 20px;
            font-weight: bolder;
        }

        #voted {
            font-family: 'Franklin Gothic Medium', 'Arial Narrow', Arial, sans-serif;
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 4px;
            cursor: pointer;
            width: 40%;
            margin-bottom: 10px;
        }
        .main {
            padding: 10px;
        }
        
        .candidate-box {
            clear: both;
            overflow: hidden;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 1px solid #ddd;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>ONLINE VOTING MANAGEMENT SYSTEM</h1>
    </div>
    <hr>
    <div class="main">
        <div class="Profile">
            <b id="maintext">User Details</b><br>
            <!-- Fix: Ensure correct path and field name for photo -->
            <center>
                <?php 
                if(isset($userdata['Photo']) && !empty($userdata['Photo']) && file_exists("../uploads/".$userdata['Photo'])) {
                    echo "<img src='../uploads/".$userdata['Photo']."' height='100px' width='100px'>";
                } else {
                    echo "<img src='../uploads/default.jpg' height='100px' width='100px'>";
                }
                ?>
            </center><br>
            <span id="text">Name: <?php echo isset($userdata['Name']) ? $userdata['Name'] : ''; ?></span><br>
            <span id="text">Number: <?php echo isset($userdata['Number']) ? $userdata['Number'] : ''; ?></span><br>
            <span id="text">Email: <?php echo isset($userdata['Email']) ? $userdata['Email'] : ''; ?></span><br>
            <span id="text">Address: <?php echo isset($userdata['Address']) ? $userdata['Address'] : ''; ?></span><br>
            <span id="text">Status: <?php echo $status; ?></span><br><br>
            <a href="../api/logout.php"><button>Log Out</button></a>
        </div>
        <div class="candidate">
            <b id="maintext">Candidates</b><br><br>
            <?php
            if (isset($candidatedata) && is_array($candidatedata) && count($candidatedata) > 0) {
                foreach ($candidatedata as $candidate) {
            ?>
                    <div class="candidate-box">
                        <!-- Fix: Ensure correct path and field name for photo -->
                        <?php 
                        if(isset($candidate['Photo']) && !empty($candidate['Photo']) && file_exists("../uploads/".$candidate['Photo'])) {
                            echo "<img style='float:left; margin-right:15px;' src='../uploads/".$candidate['Photo']."' width='100px' height='100px'>";
                        } else {
                            echo "<img style='float:left; margin-right:15px;' src='../uploads/default.jpg' width='100px' height='100px'>";
                        }
                        ?>
                        <b id="text">Group Name: <?php echo isset($candidate['Name']) ? $candidate['Name'] : ''; ?></b><br>
                        <b>Votes: <?php echo isset($candidate['Vote']) ? $candidate['Vote'] : 0; ?></b><br>
                        
                        <form action="../api/vote.php" method="post">
                            <input type="hidden" name="cvotes" value="<?php echo isset($candidate['Vote']) ? $candidate['Vote'] : 0; ?>">
                            <input type="hidden" name="cid" value="<?php echo isset($candidate['id']) ? $candidate['id'] : 0; ?>">
                            <?php
                            if($userdata['Status'] == 0) {
                                ?>
                                <button type="submit" name="votebtn">Vote</button>
                                <?php
                            } else {
                                if(isset($userdata['Vote']) && $userdata['Vote'] == $candidate['id']) {
                                    ?>
                                    <button disabled type="button" id="voted">Voted</button>
                                    <?php
                                } else {
                                    ?>
                                    <button disabled type="button">Vote</button>
                                    <?php
                                }
                            }
                            ?>
                        </form>
                    </div>
            <?php
                }
            } else {
                echo "<p>No candidates available.</p>";
            }
            ?>
        </div>
    </div>
</body>

</html>