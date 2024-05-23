<?php

use LDAP\Result;

include "db_conn.php";
session_start();

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $username = $_POST['username'];
    $password = $_POST['password'];
    $confirmPassword = $_POST['password1'];
   
    // get the recruter is
    $recruiterID = isset($_SESSION['recruiterID']) ? $_SESSION['recruiterID'] : '';

    if ($password !== $confirmPassword) {
        // Passwords don't match, display error message
        echo "<script>alert('Passwords do not match. Please re-enter your password.'); window.location='updatepassword.php';</script>";
    } else {
        // Update the password for the specific username
        $sql = "UPDATE `recruiter` SET `password` = '$password' WHERE `recruiter_id` = '$recruiterID'";
        $result = mysqli_query($conn, $sql);
    
        // Check if the update was successful
        if (mysqli_affected_rows($conn) > 0) {
            echo "<script>alert('Your Password is Updated Successfully.'); window.location='search.php';</script>";
            exit();
        } else {
            echo "<script>alert('Failed to update password. Please try again later.'); window.location='updatepassword.php';</script>";
        }
    }
    
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Password</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <style>
        /* Custom CSS */
        .form-container {
            border: 1px solid #ced4da;
            border-radius: 5px;
            padding: 20px;
        }
    </style>
</head>

<body>

    <nav class="navbar navbar-light justify-content-center fs-3 mb-5" style="background-color: #00ff5573;">
        <span style="font-size: 1.5rem;">
            <h3>Update New Password</h3>
        </span>
    </nav>
    <div class="container">
        <div class="d-flex align-items-center">
            <?php
            if (isset($_SESSION['username'])) {
                // Display an active dot and the username
                echo '<span><i class="fas fa-circle text-success me-1"></i>' . $_SESSION['username'] . '</span>';
            }
            ?>
        </div>
    </div>
    <div class="container d-flex justify-content-center align-items-center" style="min-height: 80vh;">
        <div class="form-container">
            <div class="text-center mb-4">
                <p class="text-muted">Complete the form below to enter a new Password (NOT 12345)</p>
            </div>
            <div class="form-container align-items-center">
            <form method="post">
                <div class="mb-4">
                    <label class="form-label">Username:</label>
                    <input type="text" class="form-control" name="username" required="" style="width: 30ch;" value="<?php echo isset($_SESSION['username']) ? $_SESSION['username'] : ''; ?>" readonly>
                </div>
                <div class="mb-4">
                    <label class="form-label">New Password:</label>
                    <input type="password" class="form-control" name="password" required="" style="width: 30ch;">
                </div>
                <div class="mb-3">
                    <label class="form-label">Confirm Password:</label>
                    <input type="password" class="form-control" name="password1" required="" style="width: 30ch;">
                </div>
                <div class="mb-3 d-flex justify-content-between">
                    <button type="submit" class="btn btn-success" name="submit">Save</button>
                    <a href="index.php" class="btn btn-danger">Cancel</a>
                </div>
            </form>
        </div>
        </div>
    </div>
    <!-- Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>

</body>

</html>