<?php
// Include database connection file
include "db_conn.php";

// Start session
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Fetch user data from the database
    $sql = "SELECT r.recruiter_ID, p.first_name, p.last_name, p.occupation
            FROM recruiter r
            JOIN person p ON r.person_ID = p.person_ID
            WHERE r.username = ? AND r.password = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        // Fetch the user data from the result set
        $row = $result->fetch_assoc();

        // Store username and recruiter ID in session
        $_SESSION['username'] = $row['first_name'] . ' ' . $row['last_name'];
        $_SESSION['recruiterID'] = $row['recruiter_ID']; // Recruiter Id for updating Candidate tables
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
                header("Location: super_user.php");
                exit();
            } else {
                // Redirect to search.php for regular users
                header("Location: search.php");
                exit();
            }
        }
    } else {
        // Invalid login
        header("Location: index2.php?msg=Wrong username or password");
        exit();
    }

    // Close statement
    $stmt->close();
}

// Close database connection
$conn->close();
?>
