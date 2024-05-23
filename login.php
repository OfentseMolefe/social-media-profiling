<?php
// Include database connection file
include "db_conn.php";

// Start session
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Fetch user data from the database
    $sql = "SELECT * FROM recruiter WHERE email = '$username' AND password = '$password'";
    $result = $conn->query($sql);
    if ($result->num_rows == 1) {
        // Fetch the user data from the result set
        $row = $result->fetch_assoc();

        // Store username and recruiter ID in session
        //$_SESSION['username'] = $username;
        $last_name = $row['last_name'];
        $first_name = $row['first_name'];

        $_SESSION['username'] = $first_name.' '.$last_name;
        $_SESSION['recruiterID'] = $row['recruiter_ID']; // Recruiter Id for updating Candidate tables
        // You can get the recruiter details here for inserting them into a session
        $_SESSION['occupationRec'] = $row['occupation']; 

        // Check if the password is "12345"
        if ($password == "12345") {
            // Redirect to updatepassword.php  
            header("Location: updatepassword.php");
            exit();
        } else {
            // Check the user's occupation
            if ($row["occupation"] == "Admin") {
                // Redirect to admin.php for admin users
                header("Location: admin.php");
                exit();
            } else {
                // Redirect to search.php for regular users
                header("Location: applicants.php");
                exit();
            }
        }
    } else {
        // Invalid login
        header("Location: index2.php?msg=Wrong username or password");
        exit();
    }
}

// Close database connection
$conn->close();
